<?php
// app/Http/Controllers/WasteGuideController.php

namespace App\Http\Controllers;

use App\Models\WasteCategory;
use Illuminate\Http\Request;

class WasteGuideController extends Controller
{
    private array $keywords = [
        'dry'       => ['paper', 'cardboard', 'glass', 'metal', 'tin', 'newspaper', 'magazine', 'carton'],
        'wet'       => ['food', 'vegetable', 'fruit', 'organic', 'kitchen', 'leftover', 'cooked', 'raw', 'garden'],
        'plastic'   => ['plastic', 'bottle', 'bag', 'wrapper', 'container', 'polythene', 'polybag'],
        'ewaste'    => ['phone', 'mobile', 'laptop', 'computer', 'battery', 'charger', 'wire', 'cable', 'electronic', 'device'],
        'hazardous' => ['chemical', 'acid', 'paint', 'oil', 'solvent', 'fertilizer', 'pesticide', 'bleach'],
        'medical'   => ['syringe', 'needle', 'medicine', 'medical', 'hospital', 'bandage', 'glove', 'mask'],
    ];

    public function index()
    {
        $categories = WasteCategory::all();
        return view('waste-guide.index', compact('categories'));
    }

    /**
     * AJAX: get guidance based on user description.
     */
    public function guide(Request $request)
    {
        $request->validate(['description' => 'required|string|max:500']);
        $input = strtolower($request->description);

        $matched = null;
        foreach ($this->keywords as $type => $words) {
            foreach ($words as $word) {
                if (str_contains($input, $word)) {
                    $matched = $type;
                    break 2;
                }
            }
        }

        $category = WasteCategory::where('slug', $matched ?? 'dry-waste')->first()
            ?? WasteCategory::first();

        return response()->json([
            'category'             => $category,
            'disposal_instructions'=> $category->disposal_instructions,
            'recycling_tips'       => $category->recycling_tips,
        ]);
    }
}
