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
        Schema::table('app_users', function (Blueprint $table) {
            $table->date('fixation_date')->nullable();
            $table->date('issue_order_date')->nullable();
            $table->boolean('is_benifit')->default(0);
            $table->text('benefit_document')->nullable(); // Store file path
            $table->text('fixation_document')->nullable(); // Store file path
        });
    }

    public function down()
    {
        Schema::table('app_users', function (Blueprint $table) {
            $table->dropColumn([
                'fixation_date',
                'issue_order_date',
                'is_benifit',
                'benefit_document',
                'fixation_document',
            ]);
        });
    }
};
