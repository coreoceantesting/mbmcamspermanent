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
        Schema::table('app_users', function (Blueprint $table) {
            $table->unsignedTinyInteger('employee_type')->after('is_employee')->default(1)->comment('0 = contractual, 1 = permanent');
            $table->string('aadhaar_no', 20)->after('mobile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_users', function (Blueprint $table) {
            $table->dropColumn('employee_type');
            $table->dropColumn('aadhaar_no');
        });
    }
};
