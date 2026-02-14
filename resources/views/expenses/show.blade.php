@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Expense Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Category: {{ $expense->category }}</h5>
            <p class="card-text"><strong>Amount:</strong> {{ $expense->amount }}</p>
            <p class="card-text"><strong>Date:</strong> {{ $expense->date }}</p>
            <p class="card-text"><strong>Notes:</strong> {{ $expense->notes }}</p>
        </div>
    </div>
    <a href="{{ route('expenses.index') }}" class="btn btn-secondary mt-3">Back to List</a>
    <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning mt-3">Edit</a>
</div>
@endsection
