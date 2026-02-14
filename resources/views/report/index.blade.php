@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container-fluid">
    <form method="GET" action="" class="row g-3 align-items-end mb-4">
        <div class="col-auto">
            <label for="filter_type" class="form-label mb-0">View By</label>
            <select class="form-select" id="filter_type" name="filter_type" onchange="toggleDateInputs()">
                <option value="month" {{ request('filter_type', 'month') == 'month' ? 'selected' : '' }}>Month</option>
                <option value="year" {{ request('filter_type') == 'year' ? 'selected' : '' }}>Year</option>
                <option value="range" {{ request('filter_type') == 'range' ? 'selected' : '' }}>Custom Range</option>
            </select>
        </div>
        <div class="col-auto filter-date-month" style="display: none;">
            <label for="month" class="form-label mb-0">Month</label>
            <input type="month" class="form-control" id="month" name="month" value="{{ request('month', now()->format('Y-m')) }}">
        </div>
        <div class="col-auto filter-date-year" style="display: none;">
            <label for="year" class="form-label mb-0">Year</label>
            <input type="number" min="2000" max="2100" class="form-control" id="year" name="year" value="{{ request('year', now()->year) }}">
        </div>
        <div class="col-auto filter-date-range" style="display: none;">
            <label for="date_from" class="form-label mb-0">From</label>
            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
        </div>
        <div class="col-auto filter-date-range" style="display: none;">
            <label for="date_to" class="form-label mb-0">To</label>
            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
    <script>
        function toggleDateInputs() {
            var type = document.getElementById('filter_type').value;
            document.querySelector('.filter-date-month').style.display = (type === 'month') ? '' : 'none';
            document.querySelector('.filter-date-year').style.display = (type === 'year') ? '' : 'none';
            document.querySelectorAll('.filter-date-range').forEach(function(e){ e.style.display = (type === 'range') ? '' : 'none'; });
        }
        document.addEventListener('DOMContentLoaded', function() {
            toggleDateInputs();
        });
    </script>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Income ({{ $displayMonth ?? $month }})</h5>
                    <p class="card-text fs-3">₹{{ number_format($totalIncome, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Expense ({{ $displayMonth ?? $month }})</h5>
                    <p class="card-text fs-3">₹{{ number_format($totalExpense, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">Recurring Expenses ({{ $displayMonth ?? $month }})</h5>
                    <p class="card-text fs-3">₹{{ number_format($totalRecurring, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">Monthly Income & Expense Chart</div>
                <div class="card-body justify-content-center d-flex" style="max-width:540px;margin:auto;flex-direction:column;">
                    <!-- Chart type submenu -->
                    <ul class="nav nav-pills mb-3" id="chartTypeTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="bar-tab" data-type="bar" type="button">Bar</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="line-tab" data-type="line" type="button">Line</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pie-tab" data-type="pie" type="button">Pie</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="doughnut-tab" data-type="doughnut" type="button">Doughnut</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="polarArea-tab" data-type="polarArea" type="button">Polar Area</button>
                        </li>
                    </ul>
                    <canvas id="incomeExpenseChart" height="320" style="max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">Income Details</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Source</th>
                                <th>Amount</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($incomes as $income)
                                <tr>
                                    <td>{{ $income->date }}</td>
                                    <td>{{ $income->source ?? '-' }}</td>
                                    <td>₹{{ number_format($income->amount, 2) }}</td>
                                    <td>{{ $income->notes }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">Expense Details</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $expense)
                                <tr>
                                    <td>{{ $expense->date }}</td>
                                    <td>{{ $expense->category }}</td>
                                    <td>₹{{ number_format($expense->amount, 2) }}</td>
                                    <td>{{ $expense->notes }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('incomeExpenseChart').getContext('2d');
    const chartData = {
        labels: ['Income', 'Expense', 'Recurring Expense'],
        datasets: [{
            label: 'Amount',
            data: [{{ $totalIncome }}, {{ $totalExpense }}, {{ $totalRecurring }}],
            backgroundColor: [
                'rgba(40, 167, 69, 0.7)',
                'rgba(220, 53, 69, 0.7)',
                'rgba(255, 193, 7, 0.7)'
            ],
            borderColor: [
                'rgba(40, 167, 69, 1)',
                'rgba(220, 53, 69, 1)',
                'rgba(255, 193, 7, 1)'
            ],
            borderWidth: 1
        }]
    };

    let chartType = 'bar';
    let chart = new Chart(ctx, {
        type: chartType,
        data: chartData,
        options: getOptions(chartType),
    });

    // Tab switching logic
    document.querySelectorAll('#chartTypeTabs button[data-type]').forEach(btn => {
        btn.addEventListener('click', function() {
            chartType = this.getAttribute('data-type');
            // Update tab UI
            document.querySelectorAll('#chartTypeTabs button').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            // Replace chart
            chart.destroy();
            chart = new Chart(ctx, {
                type: chartType,
                data: chartData,
                options: getOptions(chartType),
            });
        });
    });

    function getOptions(type) {
        let options = {
            responsive: true,
            plugins: {
                legend: { display: type === 'pie' || type === 'doughnut' || type === 'polarArea' },
                title: { display: false }
            }
        };
        if (type === 'bar' || type === 'line') {
            options.scales = {
                y: { beginAtZero: true }
            };
            options.plugins.legend.display = false;
        }
        return options;
    }
</script>
@endpush
@endsection
