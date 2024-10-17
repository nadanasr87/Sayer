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
        Schema::table('delivery_men', function (Blueprint $table) {
            $table->string('national')->nullable();
            $table->string('dateOfBirth')->nullable();
            $table->string('drivingLicense_issueDate')->nullable();
            $table->string('drivingLicense_expiryDate')->nullable();
            $table->string('carNumber')->nullable();
            $table->string('carTypeId')->nullable();
            $table->string('plateNumber')->nullable();
            $table->string('vehicleModel')->nullable();
            $table->string('vehicleBrand')->nullable();
            $table->string('vehicleWeight')->nullable();
            $table->string('vehicleLoad')->nullable();
            $table->string('vehicleColor')->nullable();
            $table->string('manufacturingYear')->nullable();
            $table->string('serialNumber')->nullable();
            $table->string('registrationType')->nullable();
            $table->string('chassisNumber')->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
