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
            $table->unsignedTinyInteger('is_latemark_updated')->default(0)->comment(' 0 = latemark calculated, 1 = no calculated')->after('is_latemark');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('punches', function (Blueprint $table) {
            $table->dropColumn('is_latemark_updated');
        });
    }
};
