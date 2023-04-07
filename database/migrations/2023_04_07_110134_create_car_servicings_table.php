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
        Schema::create('car_servicings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('garage_id');
            $table->unsignedBigInteger('car_id');
            $table->unsignedBigInteger('service_type_id');
            $table->foreign('garage_id')->references('id')->on('garages')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_servicings');
    }
};
