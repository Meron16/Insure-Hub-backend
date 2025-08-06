<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\transactions;
use App\Models\user_policies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(user_policies $userPolicy)
    {
        $transactions = $userPolicy->transactions()->paginate(10);
        return TransactionResource::collection($transactions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, user_policies $userPolicy)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:credit_card,bank_transfer,mobile_money,wallet',
            'payment_method_details' => 'sometimes|array',
            'status' => 'sometimes|in:success,failed,pending,refunded',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Generate transaction reference
        $transactionRef = 'TXN-' . strtoupper(uniqid());

        $transaction = $userPolicy->transactions()->create(array_merge($request->all(), [
            'user_id' => $userPolicy->user_id,
            'policy_id' => $userPolicy->policy_id,
            'transaction_reference' => $transactionRef,
            'status' => $request->status ?? 'pending'
        ]));

        // Update user policy if payment is successful
        if ($transaction->status === 'success') {
            $userPolicy->update([
                'paid_amount' => $userPolicy->paid_amount + $transaction->amount,
                'last_payment_date' => now(),
                'status' => 'active'
            ]);

            // Update next payment date if not one-time payment
            if ($userPolicy->payment_frequency !== 'one-time') {
                $nextPaymentDate = match ($userPolicy->payment_frequency) {
                    'monthly' => now()->addMonth(),
                    'quarterly' => now()->addMonths(3),
                    'yearly' => now()->addYear(),
                    default => null,
                };

                if ($nextPaymentDate) {
                    $userPolicy->update(['next_payment_date' => $nextPaymentDate]);
                }
            }
        }

        return new TransactionResource($transaction);
    }

    /**
     * Display the specified resource.
     */
    public function show(transactions $transaction)
    {
        return new TransactionResource($transaction->load(['user', 'policy', 'userPolicy']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, transactions $transaction)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:success,failed,pending,refunded',
            'failure_reason' => 'nullable|string|required_if:status,failed',
            'invoice_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transaction->update($request->all());

        return new TransactionResource($transaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(transactions $transaction)
    {
        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted successfully']);
    }
}