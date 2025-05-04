<?php

namespace App\Http\Livewire;

use App\Models\LeaveRequest as ModelsLeaveRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class LeaveApplicationDepartment extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Use bootstrap styling for pagination
    protected $listeners = ['$refresh']; // Listen for the refresh event to update the component

    // TABLE FUNCTIONALITY
    public $records_per_page = 10; // Set the default records per page
    public $search = ''; // Search query
    public $column = 'user.created_at'; // Default column for sorting
    public $order = 'DESC'; // Default sorting order

    public function render()
    {
        // Get the authenticated user
        $authUser = Auth::user();
        $employeeType =1;
        // Check if the authenticated user has an admin role
        $isAdmin = $authUser->hasRole(['Admin', 'Super Admin']);

        // Fetch users from the same department and eager load their leave data
        $usersQuery = User::where('department_id', $authUser->department_id)
            ->with(['userLeaves', 'leaveRequests']) // Eager load leave data
            ->where(function ($query) {
                // Apply the search query to the employee code and name
                $query->where('emp_code', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%');
            })
            // ->where('employee_type', $employeeType);

        // Paginate users
        $users = $usersQuery->paginate($this->records_per_page);

        return view('livewire.leave-application-department', [
            'users' => $users,
            'isAdmin' => $isAdmin, // Pass the admin status to the view
        ]);
    }

    // Method to handle the sorting of columns
    public function sorting($column, $order)
    {
        if ($this->column == $column) {
            // Toggle sorting order if the same column is clicked again
            $this->order = $order == 'ASC' ? 'DESC' : 'ASC';
        } else {
            // Set the new column and order
            $this->column = $column;
            $this->order = $order;
        }
    }

    // Method to boot the component
    public function boot()
    {
        // Set the type constant based on the page type parameter in the request
        $this->type_const = strtoupper('LEAVE_STATUS_IS_' . (request()->page_type ?? 'pending'));
    }
}
