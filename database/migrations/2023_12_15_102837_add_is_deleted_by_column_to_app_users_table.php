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
            $table->foreignId('deleted_by')->nullable()->after('present_address')->constrained('app_users');
            $table->foreignId('updated_by')->nullable()->after('present_address')->constrained('app_users');
            $table->foreignId('created_by')->nullable()->after('present_address')->constrained('app_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_users', function (Blueprint $table) {
            $table->dropForeign('app_users_created_by_foreign');
            $table->dropColumn('created_by');
            $table->dropForeign('app_users_updated_by_foreign');
            $table->dropColumn('updated_by');
            $table->dropForeign('app_users_deleted_by_foreign');
            $table->dropColumn('deleted_by');
        });
    }
};
