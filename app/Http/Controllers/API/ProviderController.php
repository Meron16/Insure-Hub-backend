<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Http\Resources\ProviderResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $providers = Provider::with(['user', 'bankDetails', 'documents'])->paginate(10);
        return ProviderResource::collection($providers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,user_id',
            'company_name' => 'required|string|max:255',
            'license_number' => 'required|string|max:100|unique:providers',
            'website_url' => 'nullable|url',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $provider = Provider::create($request->all());

        return new ProviderResource($provider->load(['user', 'bankDetails', 'documents']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {
        return new ProviderResource($provider->load(['user', 'bankDetails', 'documents', 'policies']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Provider $provider)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'sometimes|string|max:255',
            'license_number' => 'sometimes|string|max:100|unique:providers,license_number,' . $provider->provider_id . ',provider_id',
            'website_url' => 'nullable|url',
            'contact_person' => 'sometimes|string|max:255',
            'contact_email' => 'sometimes|email|max:255',
            'contact_phone' => 'sometimes|string|max:20',
            'is_approved' => 'sometimes|boolean',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $provider->update($request->all());

        return new ProviderResource($provider);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider $provider)
    {
        $provider->delete();
        return response()->json(['message' => 'Provider deleted successfully']);
    }

    /**
     * Approve a provider
     */
    public function approve(Provider $provider)
    {
        $provider->update([
            'is_approved' => true,
            'approval_date' => now()
        ]);

        return new ProviderResource($provider);
    }
}