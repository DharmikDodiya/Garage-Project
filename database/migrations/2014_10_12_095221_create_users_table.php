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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('garage_id')->nullable();
            $table->unsignedBigInteger('service_type_id')->nullable();
            $table->string('first_name',30);
            $table->string('last_name',30);
            $table->string('email',50)->unique();
            $table->string('password');
            $table->enum('type',['customer','mechanic','garage owner','admin'])->default('customer');
            $table->string('billable_name',40);
            $table->text('address1');
            $table->longText('address2')->nullable();
            $table->bigInteger('zip_code');
            $table->bigInteger('phone')->unique();
            $table->string('profile_picture');
            $table->string('token');
            $table->timestamp('email_verified_at')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
