<?php

namespace App\Http\Livewire;

use App\Models\LeaveRequest as ModelsLeaveRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;


class LeaveApplicationOverlap extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['$refresh'];

    // TABLE FUNCTIONALITY
    public $records_per_page = 10;
    public $search = '';
    public $column = 'from_date';
    public $order = 'ASC';
    public $type_const = 'pending';

    public function render()
    {
        $authUser = Auth::user();
        $isAdmin = $authUser->hasRole(['Admin', 'Super Admin']);

        $leaveRequests = Search::add(
                                ModelsLeaveRequest::with('leaveType', 'document','user.userLeaves')
                                ->whereExists(function ($query) {
                                    $query->selectRaw(1)
                                        ->from('leave_requests as lr2')
                                        ->whereColumn('leave_requests.id', '!=', 'lr2.id')
                                        ->whereColumn('leave_requests.from_date', '<=', 'lr2.to_date')
                                        ->whereColumn('leave_requests.to_date', '>=', 'lr2.from_date');
                                })
                                    ->withWhereHas('user', fn($qr)=> $qr
                                        ->when( !$isAdmin, fn($q)=> $q->where('sub_department_id', $authUser->sub_department_id))
                                        ->with('ward', 'clas', 'department')
                                        ->whereIn('clas_id',[1,2])
                                        ->where('employee_type',1)
                                    )
                                    ->when(!$isAdmin, fn($qr) => $qr
                                        ->withWhereHas('approvalHierarchy', fn($q) => $q
                                            // ->where('approver_user_id', $authUser->id)
                                            ->where('status', constant("App\Models\LeaveRequest::$this->type_const"))
                                        )
                                    )
                                    ->whereIsApproved( constant("App\Models\LeaveRequest::$this->type_const") )
                                    // ->whereNot('leave_type_id', '7')
                                    // ->orWhere( fn($q) => $q->where('leave_type_id', null)->whereIsApproved(constant("App\Models\LeaveRequest::$this->type_const")) )
                                    ->latest(),
                                [ 'id', 'from_date', 'to_date', 'no_of_days', 'remark', 'user.name', 'user.emp_code' ]
                            )
                            ->paginate((int)$this->records_per_page)
                            ->beginWithWildcard()
                            ->search($this->search);

        return view('livewire.leave-application-overlap')->with(['leaveRequests'=> $leaveRequests, 'isAdmin' => $isAdmin]);
    }

    public function boot()
    {
        $this->type_const = 'LEAVE_STATUS_IS_PENDING';
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
