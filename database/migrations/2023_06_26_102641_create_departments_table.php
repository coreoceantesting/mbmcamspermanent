<?php

use App\Models\Department;
use App\Models\User;
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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Department::class)->nullable()->comment('This will be parent of all, better for nested departments')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name', 100);
            $table->string('initial', 100)->nullable();
            $table->unsignedTinyInteger('level')->default(1)->comment('add subsequent numbers as per nesting of department');
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
        Schema::dropIfExists('departments');
    }
};
