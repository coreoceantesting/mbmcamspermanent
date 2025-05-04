<?php

namespace App\Http\Livewire;

use App\Models\LeaveRequest as ModelsLeaveRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;


class LeaveApplicationClass extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['$refresh'];

    // TABLE FUNCTIONALITY
    public $records_per_page = 10;
    public $search = '';
    public $column = 'user.created_at';
    public $order = 'DESC';
    public $type_const = 'pending';

    public function render()
    {
        $authUser = Auth::user();
        $isAdmin = $authUser->hasRole(['Admin', 'Super Admin']);

        $leaveRequests = Search::add(
                                ModelsLeaveRequest::with('leaveType', 'document','user.userLeaves')
                                    ->withWhereHas('user', fn($qr)=> $qr
                                        ->whereIn('clas_id',[1,2])
                                        // ->when( !$isAdmin, fn($q)=> $q->where('sub_department_id', $authUser->sub_department_id))
                                        ->with('ward', 'clas', 'department')
                                    )
                                    ->when(!$isAdmin, fn($qr) => $qr
                                        ->withWhereHas('approvalHierarchy', fn($q) => $q 
                                        )
                                    )
                                    // ->whereIsApproved( constant("App\Models\LeaveRequest::$this->type_const") )
                                    // ->whereNot('leave_type_id', '7')
                                    // ->orWhere( fn($q) => $q->where('leave_type_id', null)->whereIsApproved(constant("App\Models\LeaveRequest::$this->type_const")) )
                                    ->latest(),
                                [ 'id', 'from_date', 'to_date', 'no_of_days', 'remark', 'user.name', 'user.emp_code' ]
                            )
                            ->paginate((int)$this->records_per_page)
                            ->beginWithWildcard()
                            ->search($this->search);
                            

        return view('livewire.leave-application-class')->with(['leaveRequests'=> $leaveRequests, 'isAdmin' => $isAdmin]);
    }

    public function boot()
    {
        // $this->type_const = strtoupper( 'LEAVE_STATUS_IS_'.request()->page_type ?? 'pending');
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