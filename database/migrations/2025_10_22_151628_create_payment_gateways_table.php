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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();

            // Gateway information
            $table->enum('gateway_name', ['xendit', 'midtrans', 'manual'])->default('xendit');
            $table->string('external_id')->unique(); // Invoice ID from gateway
            $table->string('payment_channel')->nullable(); // E.g., QRIS, VA_BCA, OVO, etc.

            // Payment details
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('IDR');
            $table->enum('status', ['pending', 'paid', 'expired', 'failed'])->default('pending');

            // URLs
            $table->text('payment_url')->nullable();
            $table->text('invoice_url')->nullable();

            // Timestamps
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            // Webhook & callback data
            $table->text('callback_token')->nullable();
            $table->json('webhook_data')->nullable(); // Store full webhook response
            $table->json('metadata')->nullable(); // Store additional data

            $table->timestamps();

            // Indexes
            $table->index('gateway_name');
            $table->index('status');
            $table->index('external_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
