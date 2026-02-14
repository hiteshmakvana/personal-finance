<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = \App\Models\Expense::all();

        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'is_recurring' => 'nullable|boolean',
            'recurring_type' => 'nullable|string',
            'recurring_day' => 'nullable|integer',
            'recurring_end_date' => 'nullable|date',
        ]);
        $validated['is_recurring'] = $request->has('is_recurring');
        \App\Models\Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $expense = \App\Models\Expense::findOrFail($id);

        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expense = \App\Models\Expense::findOrFail($id);

        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'is_recurring' => 'nullable|boolean',
            'recurring_type' => 'nullable|string',
            'recurring_day' => 'nullable|integer',
            'recurring_end_date' => 'nullable|date',
        ]);
        $validated['is_recurring'] = $request->has('is_recurring');
        $expense = \App\Models\Expense::findOrFail($id);
        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = \App\Models\Expense::findOrFail($id);
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    /**
     * Remove multiple resources from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:expenses,id',
        ]);

        $count = \App\Models\Expense::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('expenses.index')->with('success', $count.' expenses deleted successfully.');
    }
}
