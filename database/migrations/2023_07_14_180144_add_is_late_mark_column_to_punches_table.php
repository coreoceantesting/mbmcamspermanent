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
        Schema::table('punches', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_latemark')->default(0)->comment(' 0 = Not late, 1 = Late')->after('duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('punches', function (Blueprint $table) {
            $table->dropColumn('is_latemark');
        });
    }
};
