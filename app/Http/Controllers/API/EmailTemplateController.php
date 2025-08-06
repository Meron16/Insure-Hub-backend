<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmailTemplateResource;
use App\Models\email_templates;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = email_templates::paginate(10);
        return EmailTemplateResource::collection($templates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:email_templates',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'variables' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $template = email_templates::create($request->all());

        return new EmailTemplateResource($template);
    }

    /**
     * Display the specified resource.
     */
    public function show(email_templates $template)
    {
        return new EmailTemplateResource($template);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, email_templates $template)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:email_templates,name,' . $template->template_id . ',template_id',
            'subject' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
            'variables' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $template->update($request->all());

        return new EmailTemplateResource($template);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(email_templates $template)
    {
        $template->delete();
        return response()->json(['message' => 'Email template deleted successfully']);
    }
}