<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use function App\Helpers\caseMatchTable;

class ManualSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'punches:manual-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $syncDatas = DB::table('manual_syncs')->where('running_status', 0)->limit(5)->get();

        if($syncDatas)
        {
            foreach($syncDatas as $syncData)
            {
                DB::table('manual_syncs')->where('id', $syncData->id)->update(['running_status'=> 1]);
                $timeStamp = Carbon::now()->toDateTimeString();
                $startOfDay = Carbon::today()->startOfDay();

                DB::table('punches')->where('emp_code', $syncData->emp_code)->whereDate('punch_date', '>=', $syncData->from_date)->whereDate('punch_date', '<=', $syncData->to_date)->where('punch_by', '0')->delete();

                DB::table(caseMatchTable('DeviceLogs_Processed'))
                        ->where(caseMatchTable('UserId'), $syncData->emp_code)
                        ->whereDate(caseMatchTable('LogDate'), '>=', $syncData->from_date)
                        ->whereDate(caseMatchTable('LogDate'), '<=', $syncData->to_date)
                        ->orderBy(caseMatchTable('DeviceLogId'))
                        ->chunk(200, function($datas) use ($startOfDay, $timeStamp){

                            foreach($datas as $data)
                            {
                                $punchDate = Carbon::parse($data->LogDate)->toDateString();
                                $punch = DB::table('punches')->where([ 'emp_code'=> $data->UserId, 'punch_date'=> $punchDate, 'punch_by'=> '0'])->first();

                                if(!$punch)
                                {
                                    DB::table('punches')->insert(['emp_code'=> $data->UserId, 'check_in' => $data->LogDate, 'device_id'=> $data->DeviceId, 'punch_date'=> $punchDate, 'created_at'=> $timeStamp ]);
                                }
                                else
                                {
                                    if($punch->check_out)
                                    {
                                        if(
                                            $startOfDay->diffInSeconds(substr($data->LogDate, 11)) <= $startOfDay->diffInSeconds(substr($punch->check_out, 11)) &&
                                            $startOfDay->diffInSeconds(substr($data->LogDate, 11)) >= $startOfDay->diffInSeconds(substr($punch->check_in, 11))
                                        )
                                        {  }
                                        else
                                        {
                                            $checkIn = $punch->check_in;
                                            $checkOut = $data->LogDate;
                                            if( $startOfDay->diffInSeconds(substr($data->LogDate, 11)) <= $startOfDay->diffInSeconds(substr($punch->check_in, 11)) )
                                            {
                                                $checkIn = $data->LogDate;
                                                $checkOut = $punch->check_out;
                                            }

                                            $duration = Carbon::parse($checkIn)->diffInSeconds($checkOut);
                                            DB::table('punches')->where([ 'emp_code' => $data->UserId, 'punch_date' => $punchDate, 'punch_by'=> '0'])
                                                        ->update([ 'check_in'=> $checkIn, 'check_out'=> $checkOut, 'duration'=> $duration, 'updated_at'=> $timeStamp ]);
                                        }
                                    }
                                    else
                                    {
                                        $checkIn = $punch->check_in;
                                        $checkOut = $data->LogDate;
                                        if( $startOfDay->diffInSeconds(substr($data->LogDate, 11)) <= $startOfDay->diffInSeconds(substr($punch->check_in, 11)) )
                                        {
                                            $checkIn = $data->LogDate;
                                            $checkOut = $punch->check_in;
                                        }
                                        $duration = Carbon::parse($checkIn)->diffInSeconds($checkOut);

                                        DB::table('punches')->where([ 'emp_code' => $data->UserId, 'punch_date' => $punchDate, 'punch_by'=> '0'])
                                                        ->update([ 'check_in'=> $checkIn, 'check_out'=> $checkOut, 'duration'=> $duration, 'updated_at'=> $timeStamp]);
                                    }
                                }
                                $updatableId = $data->DeviceLogId;
                            }
                        });

                DB::table('manual_syncs')->where('id', $syncData->id)->delete();
            }
        }

        $this->info('Command executed successfully!');
    }
}
