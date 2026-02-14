<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Calculate month stats
        $totalIncomeMonth = DB::table('incomes')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $totalExpensesMonth = DB::table('expenses')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $netBalanceMonth = $totalIncomeMonth - $totalExpensesMonth;

        // Calculate year stats
        $startOfYear = $now->copy()->startOfYear();
        $endOfYear = $now->copy()->endOfYear();

        $totalIncomeYear = DB::table('incomes')
            ->whereBetween('date', [$startOfYear, $endOfYear])
            ->sum('amount');

        $totalExpensesYear = DB::table('expenses')
            ->whereBetween('date', [$startOfYear, $endOfYear])
            ->sum('amount');

        $netBalanceYear = $totalIncomeYear - $totalExpensesYear;

        // Monthly trend data (per month in year)
        $monthlyIncome = [];
        $monthlyExpenses = [];
        $monthLabels = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthStart = Carbon::create($now->year, $m, 1)->startOfMonth();
            $monthEnd = Carbon::create($now->year, $m, 1)->endOfMonth();
            $monthlyIncome[] = DB::table('incomes')->whereBetween('date', [$monthStart, $monthEnd])->sum('amount');
            $monthlyExpenses[] = DB::table('expenses')->whereBetween('date', [$monthStart, $monthEnd])->sum('amount');
            $monthLabels[] = $monthStart->format('M');
        }

        // Yearly trend data (last 4 years, for demo)
        $yearLabels = [];
        $yearlyIncome = [];
        $yearlyExpenses = [];
        $curYear = $now->year;
        for ($y = $curYear - 3; $y <= $curYear; $y++) {
            $yearLabels[] = $y;
            $start = Carbon::create($y, 1, 1)->startOfYear();
            $end = Carbon::create($y, 12, 31)->endOfYear();
            $yearlyIncome[] = DB::table('incomes')->whereBetween('date', [$start, $end])->sum('amount');
            $yearlyExpenses[] = DB::table('expenses')->whereBetween('date', [$start, $end])->sum('amount');
        }

        return view('dashboard', [
            'totalIncomeMonth' => $totalIncomeMonth,
            'totalExpensesMonth' => $totalExpensesMonth,
            'netBalanceMonth' => $netBalanceMonth,
            'totalIncomeYear' => $totalIncomeYear,
            'totalExpensesYear' => $totalExpensesYear,
            'netBalanceYear' => $netBalanceYear,
            'monthLabels' => $monthLabels,
            'monthlyIncome' => $monthlyIncome,
            'monthlyExpenses' => $monthlyExpenses,
            'yearLabels' => $yearLabels,
            'yearlyIncome' => $yearlyIncome,
            'yearlyExpenses' => $yearlyExpenses,
        ]);
    }
}
