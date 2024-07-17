<?php

namespace App\Console\Commands;

use App\Models\Punch;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function App\Helpers\caseMatchTable;

class ImportPunchData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'punches:import {date?} {date2?}';            //Date should Day n Month i.e, '2023-08-25'

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import DeviceLogs_Processed data to punches table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if($this->argument('date'))
        {
            $date = Carbon::parse($this->argument('date'))->toDateString();
            // DB::table('punches')->where('punch_by', '0')->whereDate('punch_date', $date)->delete();
            DB::table(caseMatchTable('DeviceLogs_Processed'))
                            ->whereDate('LogDate', $date)
                            // ->where('UserId', '04198')
                            ->orderBy('DeviceLogId')
                            ->chunk(200, function($datas){
                                $this->importPunches($datas);
                            });
        }
        else
        {
            $lastRecord = DB::table('punches')->orderByDesc('id')->first();
            if($lastRecord)
            {
                $lastLogId = DB::table(caseMatchTable('DeviceLogs_Processed'))->where('LogDate', $lastRecord->check_out ?? $lastRecord->check_in)->where('UserId', $lastRecord->emp_code)->value('DeviceLogId');

                DB::table(caseMatchTable('DeviceLogs_Processed'))->where('DeviceLogId', '>=', $lastLogId)
                                ->orderBy('DeviceLogId')->chunk(200, function($datas){
                                    $this->importPunches($datas);
                                });
            }
            else
            {
                DB::table(caseMatchTable('DeviceLogs_Processed'))->orderBy('DeviceLogId')->where('LogDate', '>=', Carbon::now()->subMonths(2)->toDateString())
                            ->chunk(200, function($datas){
                                $this->importPunches($datas);
                            });
            }
        }

        $this->info('The punches data is imported successfully!');
        return 0;
    }



    private function importPunches($datas)
    {
        foreach($datas as $data)
        {
            // if( !is_numeric($data->UserId) )
            //     continue;

            $punchDate = Carbon::parse($data->LogDate)->toDateString();
            $timeStamp = Carbon::now()->toDateTimeString();
            $punch = Punch::where([ 'emp_code'=> $data->UserId, 'punch_date'=> $punchDate, 'type'=> '0' ])->first();

            if(!$punch)
            {
                DB::table('punches')->insert([
                    'emp_code'=> $data->UserId, 'check_in' => $data->LogDate, 'device_id'=> $data->DeviceId, 'punch_date'=> $punchDate, 'created_at'=> $timeStamp, 'updated_at'=> $timeStamp ]);
            }
            else
            {
                $punch->check_out = $data->LogDate;
                $punch->duration = Carbon::parse($punch->check_in)->diffInSeconds($data->LogDate);
                $punch->updated_at = $timeStamp;
                $punch->save();
            }
        }
    }
}
