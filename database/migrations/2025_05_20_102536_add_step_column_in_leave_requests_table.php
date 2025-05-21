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
        Schema::table('leave_approval_hierarchies', function (Blueprint $table) {
            $table->boolean('next_approval_flag')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_approval_hierarchies', function (Blueprint $table) {
            $table->dropColumn('next_approval_flag');
        });
    }
};
