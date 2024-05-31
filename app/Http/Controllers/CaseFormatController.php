<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CaseFormat;

class CaseFormatController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'prefix' => 'required|string', // Assuming prefix is required to find/update the record
            'prefix_value' => 'nullable|string',
            'prefix_date' => 'nullable|date',
            'prefix_year_format' => 'nullable|in:full,short',
            'prefix_year_only' => 'boolean',
            'prefix_month_only' => 'boolean',
            'prefix_day_only' => 'boolean',
            'suffix' => 'nullable|string',
            'suffix_value' => 'nullable|string',
            'suffix_date' => 'nullable|date',
            'suffix_year_format' => 'nullable|in:full,short',
            'suffix_year_only' => 'boolean',
            'suffix_month_only' => 'boolean',
            'suffix_day_only' => 'boolean',
            'auto_number' => 'boolean',
            'starter_number' => 'nullable|integer|min:0',
            'include_hyphens' => 'boolean',
        ]);

        // Delete any existing records
        CaseFormat::truncate();

        // Create the new record
        $created = CaseFormat::create($request->all());
        return back()->with('success', 'Record created successfully');
    }
}
