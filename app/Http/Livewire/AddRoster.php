<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeShift;
use App\Models\Shift;
use App\Models\User;
use App\Models\Ward;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class AddRoster extends Component
{
    public $emp_code, $from_date, $to_date, $is_department = 0, $su, $mo, $tu, $we, $th, $fr, $sa;
    public $employees = [], $except_this_emp_code = [], $selected_department, $selected_designation, $date_ranges = [], $day_count;
    protected $wards, $departments, $designations, $shiftLists;

    public function render()
    {
        if($this->to_date)
            $this->date_ranges = CarbonPeriod::create( Carbon::parse($this->from_date ?? Carbon::today()->startOfWeek()->toDateString()), Carbon::parse($this->to_date ?? Carbon::today()->endOfWeek()->toDateString()) )->toArray();

        return view('livewire.add-roster')->with([ 'wards'=>$this->wards, 'departments'=> $this->departments, 'shiftLists'=> $this->shiftLists, 'designations'=> $this->designations ]);
    }
    public function boot()
    {
        $this->departments = Department::whereNot('department_id', null)->orderBy('name')->get();
        $this->wards = Ward::orderBy('name')->get();
        $this->designations = Designation::orderBy('name')->get();
        $this->shiftLists = Shift::get();
    }

    public function saveShift($key)
    {
        $this->addValidate(array($key));
        $user = User::where('emp_code', $this->emp_code[$key])->first();

        $date_ranges = CarbonPeriod::create( Carbon::parse($this->from_date), Carbon::parse($this->to_date) )->toArray();
        foreach($date_ranges as $date_range)
        {
            $varName = strtolower(substr(Carbon::parse($date_range)->format('D'), 0, 2));
            $val = $this->{$varName}[$key];
            $shift = '';
            $isNight = 0;
            $toDate = Carbon::parse($date_range)->toDateString();
            if(is_numeric($val))
            {
                $shift = Shift::find( $val );
                if(Carbon::parse($shift->to_time)->lt($shift->from_time))
                {
                    $toDate = Carbon::parse($date_range)->addDay()->toDateString();
                    $isNight = 1;
                }
                else
                {
                    $toDate = Carbon::parse($date_range)->toDateString();
                }
            }

            EmployeeShift::updateOrCreate([
                    'user_id'=> $user->id,
                    'from_date'=> Carbon::parse($date_range)->toDateString(),
                ], [
                    'shift_id'=> is_numeric($val) ? $shift->id : 0,
                    'emp_code'=> $user->emp_code,
                    'to_date'=> $toDate,
                    'in_time'=> is_numeric($val) ? $shift->from_time : $val,
                    'weekday'=> $varName,
                    'is_night'=> $isNight
                ]);
        }

        array_push($this->except_this_emp_code, $this->emp_code[$key]);
        $this->fetchEmployees();
        $this->dispatchBrowserEvent('swal:modal', ['type' => 'success', 'text' => 'Shift updated successfully.']);
    }


    protected function fetchEmployees()
    {
        $this->employees = User::query()
                            ->where('is_rotational', '1')
                            ->whereActiveStatus('1')
                            ->whereNotIn('emp_code', $this->except_this_emp_code)
                            ->when($this->is_department == 1, fn($q)=> $q->where('sub_department_id', $this->selected_department) )
                            ->when($this->is_department == 2, fn($q)=> $q->where('designation_id', $this->selected_designation) )
                            // ->pluck('emp_code');
                            ->select('tenant_id', 'emp_code', 'name')->get();

        $this->emp_code = $this->employees->pluck('emp_code');
    }






    // Magic Events
    public function updatedFromDate()
    {
        $this->to_date = Carbon::parse($this->from_date)->addDays($this->day_count ?? 6)->toDateString();
    }
    public function updatedSelectedDepartment()
    {
        $this->fetchEmployees();
    }
    public function updatedSelectedDesignation()
    {
        $this->fetchEmployees();
    }
    public function updatedDayCount()
    {
        $this->to_date = Carbon::parse($this->from_date)->addDays($this->day_count ?? 6)->toDateString();
    }

    protected function addValidate($empIdArray)
    {
        $this->resetErrorBag();

        $fieldArray = [];
        $messageArray = [];
        foreach ($empIdArray as $id)
        {
            $fieldArray['su.' . $id] = 'required';
            $fieldArray['mo.' . $id] = 'required';
            $fieldArray['tu.' . $id] = 'required';
            $fieldArray['we.' . $id] = 'required';
            $fieldArray['th.' . $id] = 'required';
            $fieldArray['fr.' . $id] = 'required';
            $fieldArray['sa.' . $id] = 'required';

            $messageArray['su.' . $id . '.required'] = 'required';
            $messageArray['mo.' . $id . '.required'] = 'required';
            $messageArray['tu.' . $id . '.required'] = 'required';
            $messageArray['we.' . $id . '.required'] = 'required';
            $messageArray['th.' . $id . '.required'] = 'required';
            $messageArray['fr.' . $id . '.required'] = 'required';
            $messageArray['sa.' . $id . '.required'] = 'required';
        }
        $validator = Validator::make([
                'su' => $this->su,
                'mo' => $this->mo,
                'tu' => $this->tu,
                'we' => $this->we,
                'th' => $this->th,
                'fr' => $this->fr,
                'sa' => $this->sa,
            ], $fieldArray, $messageArray);

        if ($validator->fails())
        {
            $this->dispatchBrowserEvent('validate:scroll-to', [ 'query' => '[name="'.$validator->errors()->keys()[0].'"]'  ]);
        }
        $validator->validate();
    }
}
