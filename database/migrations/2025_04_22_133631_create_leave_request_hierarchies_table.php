<?php

use App\Models\Clas;
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
        Schema::create('leave_request_hierarchies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Clas::class)->comment('class id');
            $table->unsignedBigInteger('requester_designation_id')->comment('designation id of leave requester');
            $table->unsignedBigInteger('1_approver_designation_id')->comment('designation id of leave approver')->nullable();
            $table->unsignedBigInteger('2_approver_designation_id')->comment('designation id of leave approver')->nullable();
            $table->unsignedBigInteger('3_approver_designation_id')->comment('designation id of leave approver')->nullable();
            $table->unsignedBigInteger('4_approver_designation_id')->comment('designation id of leave approver')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_request_hierarchies');
    }
};
