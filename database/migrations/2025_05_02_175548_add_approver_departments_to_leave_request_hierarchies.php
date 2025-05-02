<?php

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
        Schema::table('leave_request_hierarchies', function (Blueprint $table) {
            $table->unsignedBigInteger('requester_department_id')->nullable()->after('requester_designation_id')->comment('department_id of requester');
            $table->unsignedBigInteger('1_approver_department_id')->nullable()->after('1_approver_designation_id')->comment('department_id of approver');
            $table->unsignedBigInteger('2_approver_department_id')->nullable()->after('2_approver_designation_id')->comment('department_id of approver');
            $table->unsignedBigInteger('3_approver_department_id')->nullable()->after('3_approver_designation_id')->comment('department_id of approver');
            $table->unsignedBigInteger('4_approver_department_id')->nullable()->after('4_approver_designation_id')->comment('department_id of approver');


            $table->foreign('requester_department_id')->references('id')->on('departments')->nullOnDelete();
            $table->foreign('1_approver_department_id')->references('id')->on('departments')->nullOnDelete();
            $table->foreign('2_approver_department_id')->references('id')->on('departments')->nullOnDelete();
            $table->foreign('3_approver_department_id')->references('id')->on('departments')->nullOnDelete();
            $table->foreign('4_approver_department_id')->references('id')->on('departments')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_request_hierarchies', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['1_approver_department_id']);
            $table->dropForeign(['2_approver_department_id']);
            $table->dropForeign(['3_approver_department_id']);
            $table->dropForeign(['4_approver_department_id']);
            $table->dropForeign(['requester_department_id']);

            // Drop columns
            $table->dropColumn([
                'requester_department_id',
                '1_approver_department_id',
                '2_approver_department_id',
                '3_approver_department_id',
                '4_approver_department_id',
            ]);
        });
    }

};
