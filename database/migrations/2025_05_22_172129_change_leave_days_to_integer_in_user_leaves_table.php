<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('user_leaves', function (Blueprint $table) {
            // Change the column type to integer
            $table->integer('leave_days')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('user_leaves', function (Blueprint $table) {
            // Revert back to decimal if needed
            $table->decimal('leave_days', 5, 2)->default(0)->change();
        });
    }
};
