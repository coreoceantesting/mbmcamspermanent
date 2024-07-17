<?php

namespace App\Http\Livewire;

use App\Models\EmployeeShift;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class EditRoster extends Component
{
    protected $listeners = ['$refresh', 'inputPropEvent'=> 'inputPropListener'];

    public $is_modal_open = false, $from_date, $to_date, $user_id, $date_ranges = [];
    public $editable_dates;

    protected $shiftLists;

    //
    public function render()
    {
        $this->date_ranges = CarbonPeriod::create( Carbon::parse($this->from_date), Carbon::parse($this->to_date) )->toArray();

        return view('livewire.edit-roster')->with(['shiftLists'=> $this->shiftLists]);
    }
    public function boot()
    {
        $this->shiftLists = Shift::get();
    }

    public function inputPropListener($params)
    {
        $this->from_date = $params['from_date'];
        $this->to_date = $params['to_date'];
        $this->user_id = $params['user_id'];
        $this->is_modal_open = true;

        $this->date_ranges = CarbonPeriod::create( Carbon::parse($params['from_date']), Carbon::parse($params['to_date']) )->toArray();
        $employeeShifts = EmployeeShift::where('user_id', $params['user_id'])->whereBetween('from_date', [$params['from_date'], $params['to_date']])->get();

        foreach($this->date_ranges as $key => $date_range)
        {
            $this->editable_dates[$key] = $employeeShifts->where('from_date', $date_range->toDateString())->first()?->shift_id;
        }
    }

    public function update()
    {
        $date_ranges = CarbonPeriod::create( Carbon::parse($this->from_date), Carbon::parse($this->to_date) )->toArray();
        $user = User::find($this->user_id);
        foreach($date_ranges as $key => $date_range)
        {
            if(!$this->editable_dates[$key])
                continue;

            $shift = 0;
            $isNight = 0;
            $toDate = Carbon::parse($date_range)->toDateString();

            if($this->editable_dates[$key] == 'rem')
            {
                EmployeeShift::where([
                            'user_id'=> $this->user_id,
                            'from_date'=> Carbon::parse($date_range)->toDateString()
                        ])->delete();
            }
            else
            {
                if(is_numeric($this->editable_dates[$key]))
                {
                    $shift = Shift::find( $this->editable_dates[$key] );
                    if($shift && Carbon::parse($shift->to_time)->lt($shift->from_time))
                    {
                        $toDate = Carbon::parse($date_range)->addDay()->toDateString();
                        $isNight = 1;
                    }
                    else
                    {
                        $toDate = Carbon::parse($date_range)->toDateString();
                    }
                }

                EmployeeShift::updateOrCreate(                        [
                            'user_id'=> $this->user_id,
                            'from_date'=> Carbon::parse($date_range)->toDateString()
                        ],[
                            'emp_code' => $user->emp_code,
                            'shift_id' => $shift ? $shift->id : 0,
                            'to_date' => $toDate,
                            'in_time' => $shift ? $shift->from_time : $this->editable_dates[$key],
                            'weekday' => strtolower(substr(Carbon::parse($date_range)->format('D'), 0, 2)),
                            'is_night'=> $isNight,
                        ]);
            }

        }

        $this->is_modal_open = false;
        $this->emitTo('roster-list', 'refreshComponent');
        $this->dispatchBrowserEvent('swal:modal', ['type' => 'success', 'text' => 'Shift updated successfully']);
    }
}
