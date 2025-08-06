<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\InsuranceCategoryResource;
use App\Models\insurance_categories;;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InsuranceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = insurance_categories::paginate(10);
        return InsuranceCategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:insurance_categories',
            'description' => 'required|string',
            'icon_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = insurance_categories::create($request->all());

        return new InsuranceCategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(insurance_categories $category)
    {
        return new InsuranceCategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, insurance_categories $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:insurance_categories,name,' . $category->category_id . ',category_id',
            'description' => 'sometimes|string',
            'icon_url' => 'nullable|url',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category->update($request->all());

        return new InsuranceCategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(insurance_categories $category)
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(insurance_categories $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        return new InsuranceCategoryResource($category);
    }
}