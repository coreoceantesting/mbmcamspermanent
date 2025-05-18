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
        Schema::table('clas', function (Blueprint $table) {
            $table->integer('retirement_age')->nullable()->after('initial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clas', function (Blueprint $table) {
            $table->dropColumn('retirement_age');
        });
    }
};
