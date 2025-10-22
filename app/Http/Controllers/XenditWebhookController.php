<?php

namespace App\Http\Controllers;

use App\Models\PaymentGateway;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    /**
     * Handle Xendit webhook callback
     */
    public function handle(Request $request)
    {
        try {
            // Log webhook data for debugging
            Log::info('Xendit Webhook Received', $request->all());

            // Get webhook data
            $externalId = $request->input('external_id');
            $status = $request->input('status');
            $invoiceId = $request->input('id');
            $paidAmount = $request->input('paid_amount');
            $paidAt = $request->input('paid_at');
            $paymentChannel = $request->input('payment_channel');

            // Find payment gateway record by external_id (our transaction reference)
            $paymentGateway = PaymentGateway::where('external_id', $invoiceId)
                ->orWhereHas('transaction', function ($query) use ($externalId) {
                    $query->where('external_id', $externalId);
                })
                ->first();

            if (! $paymentGateway) {
                Log::warning('Payment Gateway not found for webhook', [
                    'external_id' => $externalId,
                    'invoice_id' => $invoiceId,
                ]);

                return response()->json(['message' => 'Payment not found'], 404);
            }

            DB::beginTransaction();

            // Update payment gateway with webhook data
            $paymentGateway->update([
                'payment_channel' => $paymentChannel,
                'webhook_data' => $request->all(),
            ]);

            // Handle different payment statuses
            switch (strtoupper($status)) {
                case 'PAID':
                    $this->handlePaidStatus($paymentGateway, $paidAt);
                    break;

                case 'EXPIRED':
                    $this->handleExpiredStatus($paymentGateway);
                    break;

                case 'FAILED':
                    $this->handleFailedStatus($paymentGateway);
                    break;

                default:
                    Log::info('Unhandled webhook status', ['status' => $status]);
                    break;
            }

            DB::commit();

            return response()->json(['message' => 'Webhook processed successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Xendit Webhook Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Error processing webhook'], 500);
        }
    }

    /**
     * Handle PAID status
     */
    protected function handlePaidStatus(PaymentGateway $paymentGateway, ?string $paidAt): void
    {
        if ($paymentGateway->status === PaymentGateway::PAID) {
            // Already marked as paid
            return;
        }

        // Mark payment gateway as paid
        $paymentGateway->markAsPaid();

        if ($paidAt) {
            $paymentGateway->update([
                'paid_at' => \Carbon\Carbon::parse($paidAt),
            ]);
        }

        // Update related transaction
        $transaction = $paymentGateway->transaction;
        if ($transaction) {
            $transaction->update([
                'status' => Transaction::COMPLETED_STATUS,
                'paid_at' => $paidAt ? \Carbon\Carbon::parse($paidAt) : now(),
            ]);
        }

        Log::info('Payment marked as PAID', [
            'payment_gateway_id' => $paymentGateway->id,
            'transaction_id' => $transaction?->id,
        ]);
    }

    /**
     * Handle EXPIRED status
     */
    protected function handleExpiredStatus(PaymentGateway $paymentGateway): void
    {
        $paymentGateway->update([
            'status' => PaymentGateway::EXPIRED,
        ]);

        // Update related transaction
        $transaction = $paymentGateway->transaction;
        if ($transaction && $transaction->status === Transaction::PENDING_STATUS) {
            $transaction->update([
                'status' => Transaction::CANCELLED_STATUS,
            ]);
        }

        Log::info('Payment marked as EXPIRED', [
            'payment_gateway_id' => $paymentGateway->id,
            'transaction_id' => $transaction?->id,
        ]);
    }

    /**
     * Handle FAILED status
     */
    protected function handleFailedStatus(PaymentGateway $paymentGateway): void
    {
        $paymentGateway->update([
            'status' => PaymentGateway::FAILED,
        ]);

        // Update related transaction
        $transaction = $paymentGateway->transaction;
        if ($transaction && $transaction->status === Transaction::PENDING_STATUS) {
            $transaction->update([
                'status' => Transaction::FAILED_STATUS,
            ]);
        }

        Log::info('Payment marked as FAILED', [
            'payment_gateway_id' => $paymentGateway->id,
            'transaction_id' => $transaction?->id,
        ]);
    }
}
