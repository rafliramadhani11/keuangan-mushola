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
        Schema::table('transactions', function (Blueprint $table) {
            // Make user_id nullable (for online donations without login)
            $table->foreignId('user_id')->nullable()->change();

            // Add payment_gateway to payment_method enum
            $table->enum('payment_method', ['cash', 'transfer', 'payment_gateway'])->change();

            // Add external_id for tracking (Xendit invoice_id, etc)
            $table->string('external_id')->nullable()->unique()->after('reference_number');

            // Add payment_url (redirect URL for online payment)
            $table->text('payment_url')->nullable()->after('external_id');

            // Add expired_at for online payment expiration
            $table->timestamp('expired_at')->nullable()->after('transaction_date');

            // Add paid_at timestamp
            $table->timestamp('paid_at')->nullable()->after('expired_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Revert user_id to required
            $table->foreignId('user_id')->nullable(false)->change();

            // Revert payment_method enum
            $table->enum('payment_method', ['cash', 'transfer'])->change();

            // Remove new columns
            $table->dropColumn(['external_id', 'payment_url', 'expired_at', 'paid_at']);
        });
    }
};
