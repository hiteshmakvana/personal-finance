@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Expense</h1>
    <form action="{{ route('expenses.update', $expense) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="{{ $expense->amount }}" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="{{ $expense->category }}" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{ $expense->date }}" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" class="form-control">{{ $expense->notes }}</textarea>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="is_recurring" name="is_recurring" value="1" {{ $expense->is_recurring ? 'checked' : '' }} onchange="document.getElementById('recurring_fields').style.display = this.checked ? 'block' : 'none';">
            <label class="form-check-label" for="is_recurring">Is Recurring?</label>
        </div>
        <div id="recurring_fields" style="display:{{ $expense->is_recurring ? 'block' : 'none' }};">
            <div class="mb-3">
                <label for="recurring_type" class="form-label">Recurring Type</label>
                <select name="recurring_type" class="form-control">
                    <option value="monthly" {{ $expense->recurring_type == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="weekly" {{ $expense->recurring_type == 'weekly' ? 'selected' : '' }}>Weekly</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="recurring_day" class="form-label">Recurring Day</label>
                <input type="number" name="recurring_day" class="form-control" min="1" max="31" value="{{ $expense->recurring_day }}" placeholder="Day of month or week">
            </div>
            <div class="mb-3">
                <label for="recurring_end_date" class="form-label">Recurring End Date</label>
                <input type="date" name="recurring_end_date" class="form-control" value="{{ $expense->recurring_end_date }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
