<?php

use App\Models\Device;
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
        Schema::create('ward_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Ward::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('device_id');
            $table->timestamps();

            $table->foreign('device_id')
                ->references('DeviceId') // permission id
                ->on('Devices')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ward_devices');
    }
};
