<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaxRule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaxRuleController extends Controller
{
    public function index(): JsonResponse
    {
        $taxRules = TaxRule::all();
        return response()->json($taxRules);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|max:2',
            'currency' => 'required|string|max:3',
            'paye_bands' => 'required|array',
            'personal_relief' => 'required|numeric|min:0',
            'insurance_relief_rate' => 'required|numeric|min:0',
            'insurance_relief_cap' => 'required|numeric|min:0',
            'pension_cap' => 'required|numeric|min:0',
            'mortgage_cap' => 'required|numeric|min:0',
            'nssf_rate' => 'required|numeric|min:0',
            'nssf_tier1_limit' => 'required|numeric|min:0',
            'nssf_tier2_limit' => 'required|numeric|min:0',
            'nssf_max' => 'required|numeric|min:0',
            'shif_rate' => 'required|numeric|min:0',
            'shif_min' => 'required|numeric|min:0',
            'housing_levy_rate' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $taxRule = TaxRule::create($validated);
        return response()->json($taxRule, 201);
    }

    public function show(string $id): JsonResponse
    {
        $taxRule = TaxRule::findOrFail($id);
        return response()->json($taxRule);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $taxRule = TaxRule::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'country_code' => 'sometimes|required|string|max:2',
            'currency' => 'sometimes|required|string|max:3',
            'paye_bands' => 'sometimes|required|array',
            'personal_relief' => 'sometimes|required|numeric|min:0',
            'insurance_relief_rate' => 'sometimes|required|numeric|min:0',
            'insurance_relief_cap' => 'sometimes|required|numeric|min:0',
            'pension_cap' => 'sometimes|required|numeric|min:0',
            'mortgage_cap' => 'sometimes|required|numeric|min:0',
            'nssf_rate' => 'sometimes|required|numeric|min:0',
            'nssf_tier1_limit' => 'sometimes|required|numeric|min:0',
            'nssf_tier2_limit' => 'sometimes|required|numeric|min:0',
            'nssf_max' => 'sometimes|required|numeric|min:0',
            'shif_rate' => 'sometimes|required|numeric|min:0',
            'shif_min' => 'sometimes|required|numeric|min:0',
            'housing_levy_rate' => 'sometimes|required|numeric|min:0',
            'is_active' => 'sometimes|required|boolean',
        ]);

        $taxRule->update($validated);
        return response()->json($taxRule);
    }

    public function destroy(string $id): JsonResponse
    {
        $taxRule = TaxRule::findOrFail($id);
        $taxRule->delete();
        return response()->json(null, 204);
    }
}
