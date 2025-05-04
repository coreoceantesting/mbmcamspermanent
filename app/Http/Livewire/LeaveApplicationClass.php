<?php

namespace App\Http\Livewire;

use App\Models\LeaveRequest as ModelsLeaveRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class LeaveApplicationClass extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['$refresh'];

    // Table functionality
    public $records_per_page = 10;
    public $search = '';
    public $column = 'id';
    public $order = 'DESC';
    public $type_const = 'pending';

    public function render()
    {
        // Get current authenticated user
        $authUser = Auth::user();
        $isAdmin = $authUser->hasRole(['Admin', 'Super Admin']);
        
        // Query for leave requests with search, pagination, and role-based filtering
        $leaveRequests = Search::add(
            ModelsLeaveRequest::with('leaveType', 'document', 'user.userLeaves')
                ->withWhereHas('user', fn($qr) => $qr
                    ->whereIn('clas_id', [1, 2])
                    ->where('employee_type', 1) // Adjust based on your needs
                    ->with('ward', 'clas', 'department')
                )
                ->when(!$isAdmin, fn($qr) => $qr
                    ->withWhereHas('approvalHierarchy', fn($q) => $q)
                )
                // Apply dynamic sorting
                ->orderBy($this->column, $this->order)
                ->latest(),
            ['id', 'from_date', 'to_date', 'no_of_days', 'remark', 'user.name', 'user.emp_code', 'user.created_at']
        )
        // Apply search term if provided
        ->search($this->search)
        // Apply pagination with the defined records per page
        ->paginate((int)$this->records_per_page)
        ->beginWithWildcard();

        return view('livewire.leave-application-class')->with([
            'leaveRequests' => $leaveRequests,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function sorting($column, $order)
    {
        // Change the sorting order if the column is already selected
        if ($this->column == $column) {
            $this->order = $order == 'ASC' ? 'DESC' : 'ASC';
        } else {
            // Update the column and order if the column is different
            $this->column = $column;
            $this->order = $order;
        }

        // Reset pagination when sorting changes
        $this->resetPage();
    }

    public function boot()
    {
        // You can use this to handle custom logic for setting the status or other needs
        // $this->type_const = strtoupper('LEAVE_STATUS_IS_' . request()->page_type ?? 'pending');
    }
}
