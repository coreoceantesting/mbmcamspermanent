<?php

use App\Models\LeaveRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave_approval_hierarchies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hierarchy_id');
            $table->foreignIdFor(LeaveRequest::class)->constrained();
            $table->unsignedBigInteger('requester_user_id');
            $table->unsignedBigInteger('requester_designation_id');
            $table->unsignedBigInteger('requester_department_id');
            $table->unsignedBigInteger('approver_user_id');
            $table->unsignedBigInteger('approver_designation_id')->nullable();
            $table->unsignedBigInteger('approver_department_id')->nullable();
            $table->unsignedBigInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_approval_hierarchies');
    }
};
