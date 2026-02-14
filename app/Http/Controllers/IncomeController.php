<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incomes = \App\Models\Income::all();

        return view('incomes.index', compact('incomes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('incomes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'source' => 'required|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'is_recurring' => 'nullable|boolean',
            'recurring_type' => 'nullable|string',
            'recurring_day' => 'nullable|integer',
            'recurring_end_date' => 'nullable|date',
        ]);
        $validated['is_recurring'] = $request->has('is_recurring');
        \App\Models\Income::create($validated);

        return redirect()->route('incomes.index')->with('success', 'Income added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $income = \App\Models\Income::findOrFail($id);

        return view('incomes.show', compact('income'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $income = \App\Models\Income::findOrFail($id);

        return view('incomes.edit', compact('income'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'source' => 'required|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'is_recurring' => 'nullable|boolean',
            'recurring_type' => 'nullable|string',
            'recurring_day' => 'nullable|integer',
            'recurring_end_date' => 'nullable|date',
        ]);
        $validated['is_recurring'] = $request->has('is_recurring');
        $income = \App\Models\Income::findOrFail($id);
        $income->update($validated);

        return redirect()->route('incomes.index')->with('success', 'Income updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $income = \App\Models\Income::findOrFail($id);
        $income->delete();

        return redirect()->route('incomes.index')->with('success', 'Income deleted successfully.');
    }

    /**
     * Remove multiple resources from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:incomes,id',
        ]);

        $count = \App\Models\Income::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('incomes.index')->with('success', $count.' incomes deleted successfully.');
    }
}
