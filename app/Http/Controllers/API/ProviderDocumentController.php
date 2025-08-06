<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProviderDocumentResource;
use App\Models\Provider;
use App\Models\provider_documents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Provider $provider)
    {
        $documents = $provider->documents()->paginate(10);
        return ProviderDocumentResource::collection($documents);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Provider $provider)
    {
        $validator = Validator::make($request->all(), [
            'document_type' => 'required|in:license,tax_certificate,incorporation_doc,proof_of_address',
            'file_url' => 'required|url',
            'expiry_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $document = $provider->documents()->create($request->all());

        return new ProviderDocumentResource($document);
    }

    /**
     * Display the specified resource.
     */
    public function show(provider_documents $document)
    {
        return new ProviderDocumentResource($document);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, provider_documents $document)
    {
        $validator = Validator::make($request->all(), [
            'document_type' => 'sometimes|in:license,tax_certificate,incorporation_doc,proof_of_address',
            'file_url' => 'sometimes|url',
            'expiry_date' => 'nullable|date',
            'status' => 'sometimes|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $document->update($request->all());

        return new ProviderDocumentResource($document);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(provider_documents $document)
    {
        $document->delete();
        return response()->json(['message' => 'Document deleted successfully']);
    }

    /**
     * Approve a document
     */
    public function approve(provider_documents $document)
    {
        $document->update([
            'status' => 'approved'
        ]);

        return new ProviderDocumentResource($document);
    }

    /**
     * Reject a document
     */
    public function reject(Request $request, provider_documents $document)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $document->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        return new ProviderDocumentResource($document);
    }
}