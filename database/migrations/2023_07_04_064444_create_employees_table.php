<?php

use App\Models\Clas;
use App\Models\Designation;
use App\Models\Shift;
use App\Models\User;
use App\Models\Ward;
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
        Schema::create('app_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('app_users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Shift::class)->default(1)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Ward::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Clas::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Designation::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('doj')->nullable();
            $table->enum('is_ot', ['y', 'n'])->default('y')->comment('y = Yes, n = No');
            $table->enum('is_divyang', ['y', 'n'])->default('y')->comment('y = Yes, n = No');
            $table->string('permanent_address', 300)->nullable();
            $table->string('present_address', 300)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_employees');
    }
};
