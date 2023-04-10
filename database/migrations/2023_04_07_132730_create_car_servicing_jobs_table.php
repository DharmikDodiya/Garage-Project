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
        Schema::create('car_servicing_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_servicing_id');
            $table->unsignedBigInteger('mechanic_id');
            $table->unsignedBigInteger('service_type_id');
            $table->enum('status',['pending','inprogress','complete','delivered'])->default('pending');
            $table->foreign('car_servicing_id')->references('id')->on('car_servicings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mechanic_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_servicing_jobs');
    }
};
