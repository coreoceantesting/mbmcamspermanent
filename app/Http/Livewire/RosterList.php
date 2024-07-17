<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class RosterList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshComponent'=> '$refresh'];

    // TABLE FUNCTIONALITY
    public $records_per_page = 10;
    public $search = '';
    public $column = 'user.created_at';
    public $order = 'DESC';

    public $from_date, $to_date, $date_ranges, $department_id, $designation_id;

    private $departments, $designations;

    public function render()
    {
        $authUser = Auth::user();
        // $is_admin = $authUser->hasRole(['Admin', 'Super Admin']);
        $defaultShift = collect( config('default_data.shift_time') );

        if($this->to_date)
            $this->date_ranges = CarbonPeriod::create( Carbon::parse($this->from_date), Carbon::parse($this->to_date) )->toArray();

        $users = [];
        if( $this->department_id || $this->designation_id )
        {
            $users = Search::add(
                                    User::select('id', 'emp_code', 'name', 'email', 'department_id', 'sub_department_id')
                                            ->with(['department', 'empShifts'=> fn($q)=>
                                                $q->whereBetween('from_date', [$this->from_date, $this->to_date])
                                            ])
                                            ->whereIsRotational(1)
                                            ->whereIsEmployee('1')->whereActiveStatus('1')
                                            ->whereNot('id', $authUser->id)
                                            ->when( $this->department_id , fn($q)=> $q->where('sub_department_id', $this->department_id) )
                                            ->when( $this->designation_id , fn($q)=> $q->where('designation_id', $this->designation_id) )
                                            ->latest(),
                                    [ 'id', 'name', 'emp_code', 'email', 'mobile', 'department.name', 'subDepartment.name' ]
                                )
                                ->paginate((int)$this->records_per_page)
                                ->beginWithWildcard()
                                ->search($this->search);
        }

        return view('livewire.roster-list')->with(['users'=> $users, 'defaultShift'=> $defaultShift, 'departments'=> $this->departments, 'designations'=> $this->designations]);
    }

    public function boot()
    {
        $this->from_date = Carbon::today()->startOfWeek()->toDateString();
        $this->to_date = Carbon::today()->endOfWeek()->toDateString();
        $this->departments = Department::whereNot('department_id', null)->orderBy('name')->get();
        $this->designations = Designation::get();
    }

    public function editShift($user_id)
    {
        $this->emitTo('edit-roster', 'inputPropEvent', ['from_date'=> $this->from_date, 'to_date'=> $this->to_date, 'user_id'=> $user_id]);
    }








    public function sorting($column, $order)
    {
        if($this->column == $column)
        {
            $this->order = $order == 'ASC' ? 'DESC' : 'ASC';
        }
        else
        {
            $this->column = $column;
            $this->order = $order;
        }
    }



    // Magic Events
    public function updatedFromDate()
    {
        $this->to_date = Carbon::parse($this->from_date)->addDays(6)->toDateString();
    }
}
