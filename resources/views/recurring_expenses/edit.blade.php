@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Recurring Expense / Loan EMI</h1>
    <form action="{{ route('recurring-expenses.update', $recurringExpense) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="{{ $recurringExpense->amount }}" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="{{ $recurringExpense->category }}" required>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ $recurringExpense->start_date }}" required>
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date (optional, for loan EMI)</label>
            <input type="date" name="end_date" class="form-control" value="{{ $recurringExpense->end_date }}">
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" class="form-control" required>
                <option value="general" @if($recurringExpense->type == 'general') selected @endif>General</option>
                <option value="loan_emi" @if($recurringExpense->type == 'loan_emi') selected @endif>Loan EMI</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" class="form-control">{{ $recurringExpense->notes }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('recurring-expenses.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
