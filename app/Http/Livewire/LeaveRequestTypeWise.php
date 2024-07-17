<?php

namespace App\Http\Livewire;

use App\Models\LeaveRequest as ModelsLeaveRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class LeaveRequestTypeWise extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['$refresh'];

    // PROPS
    public $pageType, $type_const;

    // TABLE FUNCTIONALITY
    public $records_per_page = 10;
    public $search = '';
    public $column = 'user.created_at';
    public $order = 'DESC';

    public function render()
    {
        $authUser = Auth::user();
        $leaveRequests = Search::add(
                                ModelsLeaveRequest::with( 'leaveType', 'document')
                                ->withWhereHas('user', fn($qr)=> $qr
                                        ->with('ward', 'clas', 'department')
                                        ->when( !$authUser->hasRole(['Admin', 'Super Admin']), fn($q)=> $q->where('department_id', $authUser->department_id) ) )
                                ->whereRequestForType( constant("App\Models\LeaveRequest::$this->type_const") )
                                ->when($this->pageType == 'full_day', fn ($qr) => $qr->whereNotIn('leave_type_id', ['2','7']) )

                                    ->latest(),
                                [ 'id', 'from_date', 'to_date', 'no_of_days', 'remark', 'user.name', 'user.emp_code' ]
                            )
                            ->paginate((int)$this->records_per_page)
                            ->beginWithWildcard()
                            ->search($this->search);

        return view('livewire.leave-request-type-wise')->with(['leaveRequests'=> $leaveRequests]);
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
