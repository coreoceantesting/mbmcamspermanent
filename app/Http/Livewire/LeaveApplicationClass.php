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
                ModelsLeaveRequest::with('leaveType', 'document', 'user.userLeaves')
                    ->withWhereHas('user', fn($qr) => $qr
                        ->whereIn('clas_id', [1, 2])
                        ->where('employee_type', 1)
                        ->with('ward', 'clas', 'department')
                    )
                    ->when(!$isAdmin, fn($qr) => $qr
                        ->withWhereHas('approvalHierarchy')
                    )
                    ->orderBy($this->column, $this->order), // Use ordering
                ['id', 'from_date', 'to_date', 'no_of_days', 'remark', 'user.name', 'user.emp_code']
            )
            ->beginWithWildcard()
            ->search($this->search)
            ->paginate((int) $this->records_per_page);

        return view('livewire.leave-application-class', [
            'leaveRequests' => $leaveRequests,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function boot()
    {
        // Optional: set default status
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset to page 1 when search changes
    }

    public function updatingRecordsPerPage()
    {
        $this->resetPage(); // Reset to page 1 when per-page changes
    }

    public function sorting($column, $order)
    {
        if ($this->column == $column) {
            $this->order = $this->order === 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->column = $column;
            $this->order = $order;
        }
        $this->resetPage(); // Reset page to avoid out-of-range pagination
    }
}
