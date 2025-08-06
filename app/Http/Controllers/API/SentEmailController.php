<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\SentEmailResource;
use App\Models\email_templates;
use App\Models\sent_emails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SentEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(email_templates $template)
    {
        $emails = $template->sentEmails()->latest()->paginate(10);
        return SentEmailResource::collection($emails);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, email_templates $template)
    {
        $validator = Validator::make($request->all(), [
            'recipient_email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'status' => 'sometimes|in:sent,delivered,failed',
            'error_message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $email = $template->sentEmails()->create($request->all());

        return new SentEmailResource($email);
    }

    /**
     * Display the specified resource.
     */
    public function show(sent_emails $email)
    {
        return new SentEmailResource($email);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sent_emails $email)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:sent,delivered,failed',
            'error_message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $email->update($request->all());

        return new SentEmailResource($email);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sent_emails $email)
    {
        $email->delete();
        return response()->json(['message' => 'Sent email record deleted successfully']);
    }
}