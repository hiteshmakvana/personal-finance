@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Income Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Source: {{ $income->source }}</h5>
            <p class="card-text"><strong>Amount:</strong> {{ $income->amount }}</p>
            <p class="card-text"><strong>Date:</strong> {{ $income->date }}</p>
            <p class="card-text"><strong>Notes:</strong> {{ $income->notes }}</p>
        </div>
    </div>
    <a href="{{ route('incomes.index') }}" class="btn btn-secondary mt-3">Back to List</a>
    <a href="{{ route('incomes.edit', $income) }}" class="btn btn-warning mt-3">Edit</a>
</div>
@endsection
