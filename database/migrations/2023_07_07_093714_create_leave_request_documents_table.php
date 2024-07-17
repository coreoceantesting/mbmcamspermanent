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
        Schema::create('leave_request_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LeaveRequest::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_request_documents');
    }
};
