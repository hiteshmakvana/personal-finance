@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid mt-4">
  <div class="row">
    <!-- Unified white icon info-boxes for monthly and yearly summary -->
    <div class="col-12 col-sm-6 col-md-4">
      <div class="info-box mb-3">
        <span class="info-box-icon" style="background:#fff;"><i class="fas fa-arrow-circle-down" style="color:#111;"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Income (This Month)</span>
          <span class="info-box-number">${{ number_format($totalIncomeMonth, 2) }}</span>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
      <div class="info-box mb-3">
        <span class="info-box-icon" style="background:#fff;"><i class="fas fa-arrow-circle-up" style="color:#111;"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Expenses (This Month)</span>
          <span class="info-box-number">${{ number_format($totalExpensesMonth, 2) }}</span>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
      <div class="info-box mb-3">
        <span class="info-box-icon" style="background:#fff;"><i class="fas fa-wallet" style="color:#111;"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Net Balance (This Month)</span>
          <span class="info-box-number">${{ number_format($netBalanceMonth, 2) }}</span>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
      <div class="info-box mb-3">
        <span class="info-box-icon" style="background:#fff;"><i class="fas fa-arrow-down" style="color:#111;"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Income (This Year)</span>
          <span class="info-box-number">${{ number_format($totalIncomeYear, 2) }}</span>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
      <div class="info-box mb-3">
        <span class="info-box-icon" style="background:#fff;"><i class="fas fa-arrow-up" style="color:#111;"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Expenses (This Year)</span>
          <span class="info-box-number">${{ number_format($totalExpensesYear, 2) }}</span>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
      <div class="info-box mb-3">
        <span class="info-box-icon" style="background:#fff;"><i class="fas fa-balance-scale" style="color:#111;"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Net Balance (This Year)</span>
          <span class="info-box-number">${{ number_format($netBalanceYear, 2) }}</span>
        </div>
      </div>
    </div>
  </div>
  <!-- End summary boxes -->

  <!-- Charts Row -->
  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header border-0">
          <h3 class="card-title">Monthly Trends: Income & Expenses</h3>
        </div>
        <div class="card-body">
          <canvas id="monthlyChart" style="height: 280px;"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header border-0">
          <h3 class="card-title">Yearly Trends: Income & Expenses</h3>
        </div>
        <div class="card-body">
          <canvas id="yearlyChart" style="height: 280px;"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script>
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const yearlyCtx = document.getElementById('yearlyChart').getContext('2d');
const monthlyChart = new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: @json($monthLabels),
        datasets: [
            {
                label: 'Income',
                data: @json($monthlyIncome),
                borderColor: 'rgba(60,141,188,1)',
                backgroundColor: 'rgba(60,141,188,0.2)',
                fill: true
            },
            {
                label: 'Expense',
                data: @json($monthlyExpenses),
                borderColor: 'rgba(245,105,84,1)',
                backgroundColor: 'rgba(245,105,84,0.2)',
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: true } },
        scales: { y: { beginAtZero: true } }
    }
});
const yearlyChart = new Chart(yearlyCtx, {
    type: 'bar',
    data: {
        labels: @json($yearLabels),
        datasets: [
            {
                label: 'Income',
                data: @json($yearlyIncome),
                backgroundColor: 'rgba(40,167,69,0.8)'
            },
            {
                label: 'Expense',
                data: @json($yearlyExpenses),
                backgroundColor: 'rgba(220,53,69,0.8)'
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: true } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endpush
@endsection
