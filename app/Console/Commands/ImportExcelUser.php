<?php

namespace App\Console\Commands;

use App\Imports\ImportExcelUser as ImportsImportExcelUser;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportExcelUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-excel-user';

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
        Excel::import(new ImportsImportExcelUser(), public_path('storage/files/user_excel.xlsx'));


        $this->info('Command executed successfully!');
    }
}
