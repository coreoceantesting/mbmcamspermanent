<?php

use App\Models\Clas;
use App\Models\Designation;
use App\Models\Shift;
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
        Schema::table('app_users', function (Blueprint $table) {
            $table->string('present_address', 300)->nullable()->after('active_status');
            $table->string('permanent_address', 300)->nullable()->after('active_status');
            $table->enum('is_divyang', ['y', 'n'])->default('y')->comment('y = Yes, n = No')->after('active_status');
            $table->enum('is_ot', ['y', 'n'])->default('y')->comment('y = Yes, n = No')->after('active_status');
            $table->date('doj')->nullable()->after('active_status');
            $table->foreignIdFor(Designation::class)->after('sub_department_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Clas::class)->after('sub_department_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Shift::class)->after('sub_department_id')->default(1)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedTinyInteger('is_employee')->default(1)->comment('1 = employee, 0 = only user')->after('active_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_users', function (Blueprint $table) {
            $table->dropForeignIdFor(Shift::class);
            $table->dropColumn('shift_id');
            $table->dropForeignIdFor(Clas::class);
            $table->dropColumn('clas_id');
            $table->dropForeignIdFor(Designation::class);
            $table->dropColumn('designation_id');
            $table->dropColumn('doj');
            $table->dropColumn('is_ot');
            $table->dropColumn('is_divyang');
            $table->dropColumn('permanent_address');
            $table->dropColumn('present_address');
            $table->dropColumn('is_employee');
        });
    }
};
