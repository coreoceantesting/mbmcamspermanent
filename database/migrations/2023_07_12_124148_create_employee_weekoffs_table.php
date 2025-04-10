<?php

use App\Models\WeekDays;
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
        Schema::create('employee_weekoffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('app_users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('weekoff_1');
            $table->string('weekoff_2')->nullable();
            $table->date('start_of_week')->nullable();
            $table->date('end_of_week')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_weekoffs');
    }
};
