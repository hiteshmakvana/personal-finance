<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecurringExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recurringExpenses = \App\Models\RecurringExpense::all();
        return view('recurring_expenses.index', compact('recurringExpenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('recurring_expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'required|in:general,loan_emi',
            'notes' => 'nullable|string',
        ]);
        \App\Models\RecurringExpense::create($validated);
        return redirect()->route('recurring-expenses.index')->with('success', 'Recurring expense created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $recurringExpense = \App\Models\RecurringExpense::findOrFail($id);
        return view('recurring_expenses.show', compact('recurringExpense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $recurringExpense = \App\Models\RecurringExpense::findOrFail($id);
        return view('recurring_expenses.edit', compact('recurringExpense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'required|in:general,loan_emi',
            'notes' => 'nullable|string',
        ]);
        $recurringExpense = \App\Models\RecurringExpense::findOrFail($id);
        $recurringExpense->update($validated);
        return redirect()->route('recurring-expenses.index')->with('success', 'Recurring expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $recurringExpense = \App\Models\RecurringExpense::findOrFail($id);
        $recurringExpense->delete();
        return redirect()->route('recurring-expenses.index')->with('success', 'Recurring expense deleted successfully.');
    }
}
