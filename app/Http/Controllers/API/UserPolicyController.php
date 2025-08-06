<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserPolicyResource;
use App\Models\policies;
use App\Models\User;
use App\Models\user_policies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        $policies = $user->userPolicies()->with(['policy', 'policy.provider'])->paginate(10);
        return UserPolicyResource::collection($policies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'policy_id' => 'required|exists:policies,policy_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'payment_frequency' => 'required|in:monthly,quarterly,yearly,one-time',
            'total_premium' => 'required|numeric|min:0',
            'payment_method' => 'required|in:credit_card,bank_transfer,mobile_money',
            'auto_renew' => 'sometimes|boolean',
            'signed_document_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Generate policy number
        $policyNumber = 'POL-' . strtoupper(uniqid());

        $userPolicy = $user->userPolicies()->create(array_merge($request->all(), [
            'policy_number' => $policyNumber,
            'status' => 'pending_payment'
        ]));

        return new UserPolicyResource($userPolicy->load(['policy', 'policy.provider']));
    }

    /**
     * Display the specified resource.
     */
    public function show(user_policies $userPolicy)
    {
        return new UserPolicyResource($userPolicy->load(['user', 'policy', 'policy.provider', 'transactions']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, user_policies $userPolicy)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'payment_frequency' => 'sometimes|in:monthly,quarterly,yearly,one-time',
            'payment_method' => 'sometimes|in:credit_card,bank_transfer,mobile_money',
            'auto_renew' => 'sometimes|boolean',
            'status' => 'sometimes|in:active,expired,cancelled,pending_payment',
            'cancellation_reason' => 'nullable|string|required_if:status,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userPolicy->update($request->all());

        return new UserPolicyResource($userPolicy);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user_policies $userPolicy)
    {
        $userPolicy->delete();
        return response()->json(['message' => 'User policy deleted successfully']);
    }

    /**
     * Cancel a user policy
     */
    public function cancel(Request $request, user_policies $userPolicy)
    {
        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userPolicy->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason
        ]);

        return new UserPolicyResource($userPolicy);
    }

    /**
     * Renew a user policy
     */
    public function renew(user_policies $userPolicy)
    {
        $policy = $userPolicy->policy;

        $newUserPolicy = $userPolicy->replicate();
        $newUserPolicy->start_date = now();
        $newUserPolicy->end_date = now()->addDays($policy->duration_days);
        $newUserPolicy->status = 'pending_payment';
        $newUserPolicy->policy_number = 'POL-' . strtoupper(uniqid());
        $newUserPolicy->save();

        return new UserPolicyResource($newUserPolicy);
    }
}