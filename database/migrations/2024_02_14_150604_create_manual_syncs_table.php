<?php

use App\Models\User;
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
        Schema::create('manual_syncs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('app_users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('emp_code')->nullable();
            $table->date('from_date');
            $table->date('to_date');
            $table->unsignedTinyInteger('running_status')->default(0)->comment('0 = not running, 1 = running');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_syncs');
    }
};
