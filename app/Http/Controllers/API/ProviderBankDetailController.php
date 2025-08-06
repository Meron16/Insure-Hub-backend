<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProviderBankDetailResource;
use App\Models\Provider;
use App\Models\provider_bank_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderBankDetailController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {
        if (!$provider->bankDetails) {
            return response()->json(['message' => 'Bank details not found'], 404);
        }
        return new ProviderBankDetailResource($provider->bankDetails);
    }

    /**
     * Store or update bank details
     */
    public function storeOrUpdate(Request $request, Provider $provider)
    {
        $validator = Validator::make($request->all(), [
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'bank_name' => 'required|string|max:255',
            'branch_code' => 'nullable|string|max:50',
            'swift_code' => 'required|string|max:50',
            'iban' => 'nullable|string|max:50',
            'currency' => 'required|string|size:3',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $bankDetails = $provider->bankDetails()->updateOrCreate(
            ['provider_id' => $provider->provider_id],
            $request->all()
        );

        return new ProviderBankDetailResource($bankDetails);
    }

    /**
     * Verify bank details
     */
    public function verify(Provider $provider)
    {
        if (!$provider->bankDetails) {
            return response()->json(['message' => 'Bank details not found'], 404);
        }

        $provider->bankDetails->update([
            'is_verified' => true,
            'verified_at' => now()
        ]);

        return new ProviderBankDetailResource($provider->bankDetails);
    }

    /**
     * Remove bank details
     */
    public function destroy(Provider $provider)
    {
        $provider->bankDetails()->delete();
        return response()->json(['message' => 'Bank details deleted successfully']);
    }
}