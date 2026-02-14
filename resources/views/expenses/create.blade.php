@extends('layouts.app')

@section('content')
<div class="container mt-4" style="max-width:560px;">
    <div class="card">
        <div class="card-header bg-white border-bottom">
            <span class="h5 mb-0">Add Expense</span>
        </div>
        <div class="card-body">
            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_recurring" name="is_recurring" value="1" onchange="document.getElementById('recurring_fields').style.display = this.checked ? 'block' : 'none';">
                    <label class="form-check-label" for="is_recurring">Is Recurring?</label>
                </div>
                <div id="recurring_fields" style="display:none;">
                    <div class="mb-3">
                        <label for="recurring_type" class="form-label">Recurring Type</label>
                        <select name="recurring_type" class="form-control">
                            <option value="monthly">Monthly</option>
                            <option value="weekly">Weekly</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="recurring_day" class="form-label">Recurring Day</label>
                        <input type="number" name="recurring_day" class="form-control" min="1" max="31" placeholder="Day of month or week">
                    </div>
                    <div class="mb-3">
                        <label for="recurring_end_date" class="form-label">Recurring End Date</label>
                        <input type="date" name="recurring_end_date" class="form-control">
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Add</button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
