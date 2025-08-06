<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditLogResource;
use App\Models\audit_logs;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logs = audit_logs::with(['user'])->latest()->paginate(20);
        return AuditLogResource::collection($logs);
    }

    /**
     * Display the specified resource.
     */
    public function show(audit_logs $log)
    {
        return new AuditLogResource($log->load(['user']));
    }

    /**
     * Filter logs by entity
     */
    public function filterByEntity(Request $request)
    {
        $request->validate([
            'entity_type' => 'required|string',
            'entity_id' => 'required|integer',
        ]);

        $logs = audit_logs::where('entity_type', $request->entity_type)
            ->where('entity_id', $request->entity_id)
            ->with(['user'])
            ->latest()
            ->paginate(20);

        return AuditLogResource::collection($logs);
    }

    /**
     * Filter logs by user
     */
    public function filterByUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|uuid|exists:users,user_id',
        ]);

        $logs = audit_logs::where('user_id', $request->user_id)
            ->with(['user'])
            ->latest()
            ->paginate(20);

        return AuditLogResource::collection($logs);
    }
}