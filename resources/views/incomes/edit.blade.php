@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Income</h1>
    <form action="{{ route('incomes.update', $income) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="{{ $income->amount }}" required>
        </div>
        <div class="mb-3">
            <label for="source" class="form-label">Source</label>
            <input type="text" name="source" class="form-control" value="{{ $income->source }}" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{ $income->date }}" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" class="form-control">{{ $income->notes }}</textarea>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="is_recurring" name="is_recurring" value="1" {{ $income->is_recurring ? 'checked' : '' }} onchange="document.getElementById('recurring_fields').style.display = this.checked ? 'block' : 'none';">
            <label class="form-check-label" for="is_recurring">Is Recurring?</label>
        </div>
        <div id="recurring_fields" style="display:{{ $income->is_recurring ? 'block' : 'none' }};">
            <div class="mb-3">
                <label for="recurring_type" class="form-label">Recurring Type</label>
                <select name="recurring_type" class="form-control">
                    <option value="monthly" {{ $income->recurring_type == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="weekly" {{ $income->recurring_type == 'weekly' ? 'selected' : '' }}>Weekly</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="recurring_day" class="form-label">Recurring Day</label>
                <input type="number" name="recurring_day" class="form-control" min="1" max="31" value="{{ $income->recurring_day }}" placeholder="Day of month or week">
            </div>
            <div class="mb-3">
                <label for="recurring_end_date" class="form-label">Recurring End Date</label>
                <input type="date" name="recurring_end_date" class="form-control" value="{{ $income->recurring_end_date }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('incomes.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
