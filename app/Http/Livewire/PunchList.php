<?php

namespace App\Http\Livewire;

use App\Models\Punch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class PunchList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['$refresh'];

    // TABLE FUNCTIONALITY
    public $records_per_page = 10;
    public $search = '';
    public $column = 'created_at';
    public $order = 'DESC';

    // Below properties will be used as prop
    public $selected_ward = '';
    public $selected_department = '';
    public $from_date = '';
    public $to_date = '';


    public function render()
    {
        $authUser = Auth::user();
        $isAdmin = $authUser->hasRole(['Admin', 'Super Admin']);
        $department = $isAdmin ? $this->selected_department : $authUser->department_id;
        $ward = $isAdmin ? $this->selected_ward : $authUser->ward_id;

        $from_date = $this->from_date ?? Carbon::today()->startOfMonth()->toDateTimeString();
        $to_date = $this->to_date ?? Carbon::today()->endOfMonth()->toDateTimeString();

        $punches = Search::add(
                        Punch::whereDate('punch_date', '>=', $from_date)->whereDate('punch_date', '<=', $to_date)
                            ->withWhereHas('user', fn ($q) =>
                                    $q->with('department', 'ward')
                                    ->when( $department, fn($q)=> $q->where('department_id', $department) )
                                    ->when( $ward, fn($q)=> $q->where('ward_id', $ward) )
                            )
                            ->whereNot('emp_code', $authUser->emp_code)
                            ->orderBy($this->column, $this->order),
                                [ 'user.id', 'user.emp_code', 'user.name', 'user.department.name', 'user.ward.name' ]
                            )
                            ->paginate((int)$this->records_per_page)
                            ->beginWithWildcard()
                            ->search($this->search);

        return view('livewire.punch-list', compact('punches'));
    }

    public function boot()
    {
        $this->from_date = $this->from_date ?? Carbon::today()->startOfMonth()->toDateTimeString();
        $this->to_date = $this->to_date ?? Carbon::today()->endOfMonth()->toDateTimeString();
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
}
