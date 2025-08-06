<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\KycVerificationResource;
use App\Models\KycVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KycVerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        $verifications = $user->kycVerifications()->latest()->paginate(10);
        return KycVerificationResource::collection($verifications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'document_type' => 'required|in:passport,national_id,driver_license',
            'document_number' => 'required|string|max:100',
            'front_image_url' => 'required|url',
            'back_image_url' => 'nullable|url',
            'selfie_with_doc_url' => 'required|url',
            'expiry_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $verification = $user->kycVerifications()->create($request->all());

        // Update user account status
        $user->update(['account_status' => 'pending_kyc']);

        return new KycVerificationResource($verification);
    }

    /**
     * Display the specified resource.
     */
    public function show(KycVerification $verification)
    {
        return new KycVerificationResource($verification);
    }

    /**
     * Process KYC verification
     */
    public function process(Request $request, KycVerification $verification)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|string|required_if:status,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $verification->update(array_merge($request->all(), [
            'verified_by' => auth()->id(),
            'verified_at' => now()
        ]));

        // Update user account status
        $verification->user->update([
            'account_status' => $request->status === 'approved' ? 'active' : 'rejected'
        ]);

        return new KycVerificationResource($verification);
    }

    /**
     * Get current verification status for user
     */
    public function status(User $user)
    {
        $verification = $user->kycVerifications()->latest()->first();
        if (!$verification) {
            return response()->json(['status' => 'not_submitted']);
        }
        return new KycVerificationResource($verification);
    }
}