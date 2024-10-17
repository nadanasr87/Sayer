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


        Schema::create('bulk_stc_payouts', function (Blueprint $table) {
            $table->id();
            $table->string('PaymentReference');
            $table->string('CustomerReference');
            $table->timestamps();
        });
        Schema::create('stc_delivery_payouts', function (Blueprint $table) {
            $table->id();
            $table->string('mobile');
            $table->string('ItemReference');
            $table->float('Amount');
            $table->string('status');
            $table->foreignId('bulk_payment_id');
            $table->timestamps();
        });
        Schema::create('stc_delivery_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id');
            $table->string('mobile');
            $table->string('RefNum');
            $table->float('Amount');
            $table->string('MerchantNote');
            $table->string('BillNumber')->nullable();
            $table->string('OtpReference');
            $table->string('OtpValue');
            $table->string('StcPayPmtReference');
            $table->string('StcPayRefNum');
            $table->string('ExpiryDuration');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_payments');
    }
};
