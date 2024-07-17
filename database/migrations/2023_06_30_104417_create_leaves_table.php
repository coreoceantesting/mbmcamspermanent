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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LeaveType::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedTinyInteger('days');
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
        Schema::dropIfExists('leaves');
    }
};
