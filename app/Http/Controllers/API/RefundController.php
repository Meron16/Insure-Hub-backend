<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\RefundResource;
use App\Models\refunds;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(transactions $transaction)
    {
        $refunds = $transaction->refund()->paginate(10);
        return RefundResource::collection($refunds);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, transactions $transaction)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0|max:' . $transaction->amount,
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Generate reference number
        $referenceNumber = 'REF-' . strtoupper(uniqid());

        $refund = $transaction->refund()->create(array_merge($request->all(), [
            'processed_by' => auth()->id(),
            'status' => 'pending',
            'reference_number' => $referenceNumber
        ]));

        return new RefundResource($refund);
    }

    /**
     * Display the specified resource.
     */
    public function show(refunds $refund)
    {
        return new RefundResource($refund->load(['transaction', 'processor']));
    }

    /**
     * Process a refund
     */
    public function process(Request $request, refunds $refund)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:completed,failed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $refund->update([
            'status' => $request->status,
            'processed_at' => now()
        ]);

        // If completed, update transaction status
        if ($request->status === 'completed') {
            $refund->transaction()->update(['status' => 'refunded']);
        }

        return new RefundResource($refund);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(refunds $refund)
    {
        $refund->delete();
        return response()->json(['message' => 'Refund record deleted successfully']);
    }
}