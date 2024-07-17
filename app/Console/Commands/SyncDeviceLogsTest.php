<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use function App\Helpers\caseMatchTable;

class SyncDeviceLogsTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-device-logs-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    // public function handle()
    // {
    //     $latestId = DB::table('last_synced_ids')->where('name', 'last_log_id')->value('value') ?? 1;
    //     $updatableId = $latestId;
    //     $timeStamp = Carbon::now()->toDateTimeString();
    //     $startOfDay = Carbon::today()->startOfDay();

    //     $datas = DB::table('DeviceLogs_7_2024')
    //                         ->where('DeviceLogId', '>', $latestId)
    //                         ->limit(500)
    //                         ->get();

    //     foreach($datas as $data)
    //     {
    //         $punchDate = Carbon::parse($data->LogDate)->toDateString();
    //         $punch = DB::table('punches')->where([ 'emp_code'=> $data->UserId, 'punch_date'=> $punchDate ])->first();

    //         // Log::info($data->DeviceLogId);
    //         if(!$punch)
    //         {
    //             DB::table('punches')->insert(
    //                 ['emp_code'=> $data->UserId, 'check_in' => $data->LogDate, 'device_id'=> $data->DeviceId, 'punch_date'=> $punchDate, 'created_at'=> $timeStamp, 'updated_at'=> $timeStamp ]
    //             );
    //         }
    //         else
    //         {
    //             if($punch->check_out)
    //             {
    //                 if(
    //                     $startOfDay->diffInSeconds(substr($data->LogDate, 11)) <= $startOfDay->diffInSeconds(substr($punch->check_out, 11)) &&
    //                     $startOfDay->diffInSeconds(substr($data->LogDate, 11)) >= $startOfDay->diffInSeconds(substr($punch->check_in, 11))
    //                 )
    //                 {  }
    //                 else
    //                 {
    //                     $checkIn = $punch->check_in;
    //                     $checkOut = $data->LogDate;
    //                     if( $startOfDay->diffInSeconds(substr($data->LogDate, 11)) <= $startOfDay->diffInSeconds(substr($punch->check_in, 11)) )
    //                     {
    //                         $checkIn = $data->LogDate;
    //                         $checkOut = $punch->check_out;
    //                     }

    //                     $duration = Carbon::parse($checkIn)->diffInSeconds($checkOut);
    //                     DB::table('punches')->where([ 'emp_code' => $data->UserId, 'punch_date' => $punchDate ])
    //                                 ->update([
    //                                     'check_in'=> $checkIn,
    //                                     'check_out'=> $checkOut,
    //                                     'duration'=> $duration,
    //                                     'updated_at'=> $timeStamp,
    //                                 ]);
    //                 }
    //             }
    //             else
    //             {
    //                 $checkIn = $punch->check_in;
    //                 $checkOut = $data->LogDate;
    //                 if( $startOfDay->diffInSeconds(substr($data->LogDate, 11)) <= $startOfDay->diffInSeconds(substr($punch->check_in, 11)) )
    //                 {
    //                     $checkIn = $data->LogDate;
    //                     $checkOut = $punch->check_in;
    //                 }
    //                 $duration = Carbon::parse($checkIn)->diffInSeconds($checkOut);

    //                 DB::table('punches')->where([ 'emp_code' => $data->UserId, 'punch_date' => $punchDate ])
    //                                 ->update([
    //                                     'check_in'=> $checkIn,
    //                                     'check_out'=> $checkOut,
    //                                     'duration'=> $duration,
    //                                     'updated_at'=> $timeStamp,
    //                                 ]);
    //             }
    //         }
    //         $updatableId = $data->DeviceLogId;
    //     }

    //     DB::table('last_synced_ids')->where('name', 'last_log_id')->update(['value'=> $updatableId, 'updated_at'=> $timeStamp]);
    //     $this->info('Command executed successfully!');
    // }

    public function handle()
    {
        $latestId = DB::table('last_synced_ids')->where('name', 'last_log_id')->value('value') ?? 1;
        $updatableId = $latestId;
        $timeStamp = Carbon::now()->toDateTimeString();
        $startOfDay = Carbon::today()->startOfDay();

        DB::table('DeviceLogs_7_2024')
            ->where('DeviceLogId', '>', $latestId)
            ->orderBy('DeviceLogId')
            ->chunk(200, function ($datas) use (&$updatableId, $timeStamp, $startOfDay) {
                foreach ($datas as $data) {
                    $this->processData($data, $timeStamp, $startOfDay, $updatableId);
                }
                DB::table('last_synced_ids')->where('name', 'last_log_id')->update(['value' => $updatableId, 'updated_at' => $timeStamp]);
            });

        $this->info('Command executed successfully!');
    }

    protected function processData($data, $timeStamp, $startOfDay, &$updatableId)
    {
        $punchDate = Carbon::parse($data->LogDate)->toDateString();
        $retries = 5;

        for ($i = 0; $i < $retries; $i++) {
            try {
                DB::transaction(function () use ($data, $timeStamp, $startOfDay, $punchDate) {
                    $punch = DB::table('punches')->where(['emp_code' => $data->UserId, 'punch_date' => $punchDate])->lockForUpdate()->first();

                    if (!$punch) {
                        $this->insertPunch($data, $timeStamp, $punchDate);
                    } else {
                        $this->updatePunch($data, $punch, $timeStamp, $startOfDay, $punchDate);
                    }
                });

                // Break out of the retry loop if the transaction was successful
                break;
            } catch (\Illuminate\Database\QueryException $ex) {
                // Check if it's a deadlock error and retry if necessary
                if ($ex->errorInfo[1] == 1213) {
                    Log::warning('Deadlock detected, retrying... Attempt ' . ($i + 1));
                    sleep(1); // Briefly sleep before retrying
                } else {
                    // Rethrow the exception if it's not a deadlock
                    throw $ex;
                }
            }
        }

        $updatableId = $data->DeviceLogId;
    }

    protected function insertPunch($data, $timeStamp, $punchDate)
    {
        DB::table('punches')->insert([
            'emp_code' => $data->UserId,
            'check_in' => $data->LogDate,
            'device_id' => $data->DeviceId,
            'punch_date' => $punchDate,
            'created_at' => $timeStamp,
            'updated_at' => $timeStamp
        ]);
    }

    protected function updatePunch($data, $punch, $timeStamp, $startOfDay, $punchDate)
    {
        $checkIn = $punch->check_in;
        $checkOut = $data->LogDate;

        if ($punch->check_out) {
            if (
                $this->isWithinSameDay($startOfDay, $data->LogDate, $punch->check_in) &&
                !$this->isWithinSameDay($startOfDay, $data->LogDate, $punch->check_out)
            ) {
                $checkIn = $punch->check_in;
                $checkOut = $data->LogDate;
            } else if ($this->isBefore($startOfDay, $data->LogDate, $punch->check_in)) {
                $checkIn = $data->LogDate;
                $checkOut = $punch->check_out;
            }
        } else {
            if ($this->isBefore($startOfDay, $data->LogDate, $punch->check_in)) {
                $checkIn = $data->LogDate;
                $checkOut = $punch->check_in;
            }
        }

        $duration = Carbon::parse($checkIn)->diffInSeconds($checkOut);

        DB::table('punches')->where(['emp_code' => $data->UserId, 'punch_date' => $punchDate])->update([
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'duration' => $duration,
            'updated_at' => $timeStamp
        ]);
    }

    protected function isWithinSameDay($startOfDay, $logDate, $referenceDate)
    {
        return $startOfDay->diffInSeconds(substr($logDate, 11)) <= $startOfDay->diffInSeconds(substr($referenceDate, 11));
    }

    protected function isBefore($startOfDay, $logDate, $referenceDate)
    {
        return $startOfDay->diffInSeconds(substr($logDate, 11)) <= $startOfDay->diffInSeconds(substr($referenceDate, 11));
    }
}
