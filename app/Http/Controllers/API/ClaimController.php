<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClaimResource;
use App\Models\claims;
use App\Models\user_policies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClaimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(user_policies $userPolicy)
    {
        $claims = $userPolicy->claims()->with(['provider', 'processor'])->paginate(10);
        return ClaimResource::collection($claims);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, user_policies $userPolicy)
    {
        $validator = Validator::make($request->all(), [
            'claim_amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'incident_date' => 'required|date|before_or_equal:today',
            'incident_location' => 'required|string|max:255',
            'claim_type' => 'required|in:medical,property,liability,other',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Generate claim number
        $claimNumber = 'CLM-' . strtoupper(uniqid());

        $claim = $userPolicy->claims()->create(array_merge($request->all(), [
            'claim_number' => $claimNumber,
            'provider_id' => $userPolicy->policy->provider_id,
            'status' => 'pending'
        ]));

        return new ClaimResource($claim->load(['provider', 'processor']));
    }

    /**
     * Display the specified resource.
     */
    public function show(claims $claim)
    {
        return new ClaimResource($claim->load(['userPolicy', 'provider', 'processor']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, claims $claim)
    {
        $validator = Validator::make($request->all(), [
            'claim_amount' => 'sometimes|numeric|min:0',
            'description' => 'sometimes|string',
            'incident_date' => 'sometimes|date|before_or_equal:today',
            'incident_location' => 'sometimes|string|max:255',
            'claim_type' => 'sometimes|in:medical,property,liability,other',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $claim->update($request->all());

        return new ClaimResource($claim);
    }

    /**
     * Process a claim
     */
    public function process(Request $request, claims $claim)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected,under_review,paid',
            'approved_amount' => 'nullable|numeric|min:0|required_if:status,approved,paid',
            'rejection_reason' => 'nullable|string|required_if:status,rejected',
            'payment_date' => 'nullable|date|required_if:status,paid',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $claim->update(array_merge($request->all(), [
            'processed_by' => auth()->id(),
            'processed_at' => now()
        ]));

        return new ClaimResource($claim);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(claims $claim)
    {
        $claim->delete();
        return response()->json(['message' => 'Claim deleted successfully']);
    }
}