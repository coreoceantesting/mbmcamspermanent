<?php

namespace App\Repositories;


use App\Models\Punch;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportRepository
{

    public function store($input)
    {
        DB::beginTransaction();
            $input['duration'] = Carbon::parse($input['check_in'])->diffInSeconds($input['check_out']);
            $input['punch_by'] = '1';
            $input['created_by'] = Auth::user()->id;
            $input['type'] = '1';
            $input['is_paid'] = '1';
            $input['check_in'] = Carbon::parse($input['check_in'])->toDateTimeString();
            $input['check_out'] = Carbon::parse($input['check_out'])->toDateTimeString();

            $punch = Punch::create( Arr::only( $input, Punch::getFillables() ) );
        DB::commit();

        return $punch;
    }




}
