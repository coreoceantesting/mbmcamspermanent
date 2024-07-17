<?php

use App\Models\LeaveType;
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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('app_users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(LeaveType::class)->nullable()->constrained();
            $table->date('from_date');
            $table->date('to_date')->nullable();
            $table->unsignedTinyInteger('no_of_days')->nullable();
            $table->text('remark');
            $table->text('approver_remark')->nullable();
            $table->unsignedTinyInteger('request_for_type')->default(1)->comment('1= full day, 2= half day, 3= outpost');
            $table->enum('is_approved', ['0', '1', '2'])->default('0')->comment('0 = unapproved, 1 = approved, 2 = rejected');
            $table->foreignId('approved_by')->nullable()->constrained('app_users');
            $table->foreignId('created_by')->nullable()->constrained('app_users');
            $table->foreignId('updated_by')->nullable()->constrained('app_users');
            $table->foreignId('deleted_by')->nullable()->constrained('app_users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
