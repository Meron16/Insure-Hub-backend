<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PolicyResource;
use App\Models\policies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $policies = policies::with(['provider', 'category'])->paginate(10);
        return PolicyResource::collection($policies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:providers,provider_id',
            'category_id' => 'required|exists:insurance_categories,category_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'premium_amount' => 'required|numeric|min:0',
            'coverage_limit' => 'required|numeric|min:0',
            'deductible_amount' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'renewal_type' => 'required|in:automatic,manual,none',
            'grace_period_days' => 'integer|min:0',
            'requires_medical_check' => 'boolean',
            'min_age' => 'nullable|integer|min:0',
            'max_age' => 'nullable|integer|min:0',
            'terms_and_conditions_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $policy = policies::create($request->all());

        return new PolicyResource($policy->load(['provider', 'category']));
    }

    /**
     * Display the specified resource.
     */
    public function show(policies $policy)
    {
        return new PolicyResource($policy->load(['provider', 'category', 'userPolicies']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, policies $policy)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'premium_amount' => 'sometimes|numeric|min:0',
            'coverage_limit' => 'sometimes|numeric|min:0',
            'deductible_amount' => 'sometimes|numeric|min:0',
            'duration_days' => 'sometimes|integer|min:1',
            'renewal_type' => 'sometimes|in:automatic,manual,none',
            'grace_period_days' => 'sometimes|integer|min:0',
            'requires_medical_check' => 'sometimes|boolean',
            'min_age' => 'nullable|integer|min:0',
            'max_age' => 'nullable|integer|min:0',
            'terms_and_conditions_url' => 'nullable|url',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $policy->update($request->all());

        return new PolicyResource($policy);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(policies $policy)
    {
        $policy->delete();
        return response()->json(['message' => 'Policy deleted successfully']);
    }

    /**
     * Approve a policy
     */
    public function approve(policies $policy)
    {
        $policy->update([
            'approved_by_admin' => true,
            'approval_date' => now()
        ]);

        return new PolicyResource($policy);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(policies $policy)
    {
        $policy->update(['is_active' => !$policy->is_active]);
        return new PolicyResource($policy);
    }
}