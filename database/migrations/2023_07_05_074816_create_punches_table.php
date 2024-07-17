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
        Schema::create('punches', function (Blueprint $table) {
            $table->id();
            $table->string('emp_code', 20);
            $table->unsignedBigInteger('device_id');
            $table->dateTime('check_in');
            $table->dateTime('check_out')->nullable();
            $table->date('punch_date')->nullable();
            $table->unsignedInteger('duration')->default('0')->comment('number of hours served in a day');
            $table->enum('punch_by', ['0', '1', '2'])->default('0')->comment('0 = System Punch, 1 = Manual Punch, 2 = Leave Adjustment');
            $table->unsignedTinyInteger('type')->default('0')->comment('0 = present, 1 = Full Day Leave, 2 = Half Day Leave, 3 = Holiday, 4 = WeekOff');
            $table->enum('is_paid', ['0', '1'])->default('1')->comment('0 = Un paid, 1 = Paid');
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
        Schema::dropIfExists('punches');
    }
};
