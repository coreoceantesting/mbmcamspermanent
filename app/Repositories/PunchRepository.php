<?php

namespace App\Repositories;

use App\Models\Punch;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PunchRepository
{

    public function store($input)
    {
        DB::beginTransaction();
        $input['duration'] = Carbon::parse($input['check_in'])->diffInSeconds($input['check_out']);
        $input['punch_by'] = '1';
        $input['created_by'] = Auth::user()->id;
        $input['type'] = '1';
        $input['is_paid'] = '1';
        $input['check_in'] = $input['punch_date'].' '.$input['check_in'];
        $input['check_out'] = $input['punch_date'].' '.$input['check_out'];

        $hasPunch = Punch::whereDate('punch_date', Carbon::parse($input['punch_date']))->where('emp_code', $input['emp_code'])->first();
        if( $hasPunch )
            $hasPunch->update( Arr::only( $input, Punch::getFillables() ) );
        else
            $hasPunch = Punch::create( Arr::only( $input, Punch::getFillables() ) );
        DB::commit();

        return $hasPunch;
    }


    public function update($input, $punch)
    {
        DB::beginTransaction();
            $input['duration'] = Carbon::parse($input['check_in'])->diffInSeconds($input['check_out']);
            $input['punch_by'] = '1';
            $input['created_by'] = Auth::user()->id;
            $input['type'] = '1';
            $input['is_paid'] = '1';
            $input['check_in'] = $input['punch_date'].' '.$input['check_in'];
            $input['check_out'] = $input['punch_date'].' '.$input['check_out'];

            $punch->update( Arr::only( $input, Punch::getFillables() ) );
        DB::commit();

        return $punch;
    }




}
