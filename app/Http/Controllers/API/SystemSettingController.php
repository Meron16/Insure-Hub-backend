<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\SystemSettingResource;
use App\Models\system_settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SystemSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = system_settings::paginate(20);
        return SystemSettingResource::collection($settings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255|unique:system_settings',
            'value' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $setting = system_settings::create(array_merge($request->all(), [
            'updated_by' => auth()->id()
        ]));

        return new SystemSettingResource($setting);
    }

    /**
     * Display the specified resource.
     */
    public function show(system_settings $setting)
    {
        return new SystemSettingResource($setting);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, system_settings $setting)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $setting->update(array_merge($request->all(), [
            'updated_by' => auth()->id()
        ]));

        return new SystemSettingResource($setting);
    }

    /**
     * Get setting by key
     */
    public function getByKey($key)
    {
        $setting = system_settings::where('key', $key)->first();
        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }
        return new SystemSettingResource($setting);
    }

    /**
     * Update setting by key
     */
    public function updateByKey(Request $request, $key)
    {
        $setting = system_settings::where('key', $key)->first();
        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'value' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $setting->update(array_merge($request->all(), [
            'updated_by' => auth()->id()
        ]));

        return new SystemSettingResource($setting);
    }
}