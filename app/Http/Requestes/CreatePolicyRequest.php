<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePolicyRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role === 'provider';
    }

    public function rules()
    {
        return [
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
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Please select a category',
            'premium_amount.min' => 'Premium amount must be positive',
            'duration_days.min' => 'Duration must be at least 1 day',
        ];
    }
}