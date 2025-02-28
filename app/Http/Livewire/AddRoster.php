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
    public $emp_code, $from_date, $to_date, $is_department, $su0, $mo1, $tu2, $we3, $th4, $fr5, $sa6, $su7, $mo8, $tu9, $we10, $th11, $fr12, $sa13, $su14, $mo15, $tu16, $we17, $th18, $fr19, $sa20, $su21, $mo22, $tu23, $we24, $th25, $fr26, $sa27;
    public $employees = [], $except_this_emp_code = [], $selected_department, $selected_designation, $date_ranges = [], $day_count;
    protected $wards, $departments, $designations, $shiftLists;

    public function render()
    {
        if ($this->to_date)
            $this->date_ranges = CarbonPeriod::create(Carbon::parse($this->from_date ?? Carbon::today()->startOfWeek()->toDateString()), Carbon::parse($this->to_date ?? Carbon::today()->endOfWeek()->toDateString()))->toArray();

        return view('livewire.add-roster')->with(['wards' => $this->wards, 'departments' => $this->departments, 'shiftLists' => $this->shiftLists, 'designations' => $this->designations]);
    }
    public function boot()
    {
        $user = auth()->user();
        $userRole = $user->roles[0];
        $this->departments = Department::query()
            ->when($userRole->name == 'Clerk', fn($q) => $q->where('id', $userRole->department_id))
            ->whereIsPermanent(0)
            ->orderBy('name')->get();

        $this->wards = Ward::orderBy('name')->get();
        $this->designations = Designation::orderBy('name')->get();
        $this->shiftLists = Shift::get();
    }

    public function saveShift($key)
    {
        // $this->addValidate(array($key));
        $user = User::where('emp_code', $this->emp_code[$key])->first();

        $date_ranges = CarbonPeriod::create(Carbon::parse($this->from_date), Carbon::parse($this->to_date))->toArray();
        foreach ($date_ranges as $i => $date_range) {
            // $varName = strtolower(substr(Carbon::parse($date_range)->format('D'), 0, 2));
            $weekday  = strtolower(substr(Carbon::parse($date_range)->format('D'), 0, 2));
            $varName = $weekday . $i;

            if (!isset($this->{$varName}[$key]))
                continue;


            $val = $this->{$varName}[$key];
            $shift = '';
            $isNight = 0;
            $toDate = Carbon::parse($date_range)->toDateString();
            if (is_numeric($val)) {
                $shift = Shift::find($val);
                if (Carbon::parse($shift->to_time)->lt($shift->from_time)) {
                    $toDate = Carbon::parse($date_range)->addDay()->toDateString();
                    $isNight = 1;
                } else {
                    $toDate = Carbon::parse($date_range)->toDateString();
                }
            }

            EmployeeShift::updateOrCreate([
                'user_id' => $user->id,
                'from_date' => Carbon::parse($date_range)->toDateString(),
            ], [
                'shift_id' => is_numeric($val) ? $shift->id : 0,
                'emp_code' => $user->emp_code,
                'to_date' => $toDate,
                'in_time' => is_numeric($val) ? $shift->from_time : $val,
                'weekday' => $weekday,
                'is_night' => $isNight
            ]);
        }

        array_push($this->except_this_emp_code, $this->emp_code[$key]);
        $this->fetchEmployees();
        $this->dispatchBrowserEvent('swal:modal', ['type' => 'success', 'text' => 'Shift updated successfully.']);
    }


    protected function fetchEmployees()
    {
        $this->employees = User::query()
            ->with(['empShifts' => fn($q) => $q->select('user_id', 'shift_id', 'from_date', 'in_time')->whereBetween('from_date', [$this->from_date, $this->to_date])])
            ->where('is_rotational', '1')
            ->whereActiveStatus('1')
            // ->whereNotIn('emp_code', $this->except_this_emp_code)
            ->when($this->is_department == 1, fn($q) => $q->where('sub_department_id', $this->selected_department))
            ->when($this->is_department == 2, fn($q) => $q->where('designation_id', $this->selected_designation))
            // ->pluck('emp_code');
            // ->select('tenant_id', 'emp_code', 'name')->get();
            ->select('tenant_id', 'emp_code', 'name', 'id')->get();

        // $this->emp_code = $this->employees->pluck('emp_code');

        $this->date_ranges = CarbonPeriod::create(Carbon::parse($this->from_date ?? Carbon::today()->startOfWeek()->toDateString()), Carbon::parse($this->to_date ?? Carbon::today()->endOfWeek()->toDateString()))->toArray();
        foreach ($this->employees as $key => $employee) {
            foreach ($this->date_ranges as $i => $date_range) {
                $date = substr($date_range, 0, 10);
                $shiftVal = $employee->empShifts->where('from_date', '>=', $date)->where('from_date', '<=', $date)->first();
                if ($shiftVal) {
                    $fieldName = strtolower(substr(Carbon::parse($date_range)->format('D'), 0, 2)) . $i . '.' . $key;
                    $shiftVal = ctype_alpha($shiftVal->in_time) ? $shiftVal->in_time : $shiftVal->shift_id;
                    $this->{$fieldName} = $shiftVal;
                }
                // else{
                //     $this->emp_roster[$employee->emp_code][substr($date_range, 8,2)] = '';
                // }
            }
        }
        $empCodes = $this->employees;
        $this->emp_code = $empCodes->pluck('emp_code');
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
        foreach ($empIdArray as $id) {
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

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('validate:scroll-to', ['query' => '[name="' . $validator->errors()->keys()[0] . '"]']);
        }
        $validator->validate();
    }
}
