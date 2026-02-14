@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Recurring Expense / Loan EMI Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Category: {{ $recurringExpense->category }}</h5>
            <p class="card-text"><strong>Amount:</strong> {{ $recurringExpense->amount }}</p>
            <p class="card-text"><strong>Start Date:</strong> {{ $recurringExpense->start_date }}</p>
            <p class="card-text"><strong>End Date:</strong> {{ $recurringExpense->end_date ?? '-' }}</p>
            <p class="card-text"><strong>Type:</strong> {{ ucfirst($recurringExpense->type) }}</p>
            <p class="card-text"><strong>Notes:</strong> {{ $recurringExpense->notes }}</p>
        </div>
    </div>
    <a href="{{ route('recurring-expenses.index') }}" class="btn btn-secondary mt-3">Back to List</a>
    <a href="{{ route('recurring-expenses.edit', $recurringExpense) }}" class="btn btn-warning mt-3">Edit</a>
</div>
@endsection
