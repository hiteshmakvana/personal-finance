<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->input('filter_type', 'month');
        $month = $request->input('month', now()->format('Y-m'));
        $year = $request->input('year', now()->year);
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Default filter: current month
        $incomes = collect();
        $expenses = collect();
        $recurringExpenses = collect();
        $displayMonth = null;

        if ($filterType === 'month') {
            $incomes = \App\Models\Income::where('date', 'like', "$month%")->get();
            $expenses = \App\Models\Expense::where('date', 'like', "$month%")->get();
            $recurringExpenses = \App\Models\RecurringExpense::where(function ($q) use ($month) {
                $monthStart = now()->setDate(substr($month, 0, 4), substr($month, 5, 2), 1)->startOfMonth()->toDateString();
                $monthEnd = now()->setDate(substr($month, 0, 4), substr($month, 5, 2), 1)->endOfMonth()->toDateString();
                $q->where('start_date', '<=', $monthEnd)
                    ->where(function ($q2) use ($monthStart) {
                        $q2->whereNull('end_date')->orWhere('end_date', '>=', $monthStart);
                    });
            })->get();
            $displayMonth = $month;
        } elseif ($filterType === 'year') {
            $incomes = \App\Models\Income::whereYear('date', $year)->get();
            $expenses = \App\Models\Expense::whereYear('date', $year)->get();
            $recurringExpenses = \App\Models\RecurringExpense::where(function ($q) use ($year) {
                $yearStart = now()->setYear($year)->startOfYear()->toDateString();
                $yearEnd = now()->setYear($year)->endOfYear()->toDateString();
                $q->where('start_date', '<=', $yearEnd)
                    ->where(function ($q2) use ($yearStart) {
                        $q2->whereNull('end_date')->orWhere('end_date', '>=', $yearStart);
                    });
            })->get();
            $displayMonth = $year;
        } elseif ($filterType === 'range' && $dateFrom && $dateTo) {
            $incomes = \App\Models\Income::whereBetween('date', [$dateFrom, $dateTo])->get();
            $expenses = \App\Models\Expense::whereBetween('date', [$dateFrom, $dateTo])->get();
            $recurringExpenses = \App\Models\RecurringExpense::where(function ($q) use ($dateFrom, $dateTo) {
                $q->where('start_date', '<=', $dateTo)
                    ->where(function ($q2) use ($dateFrom) {
                        $q2->whereNull('end_date')->orWhere('end_date', '>=', $dateFrom);
                    });
            })->get();
            $displayMonth = $dateFrom.' to '.$dateTo;
        }

        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $totalRecurring = $recurringExpenses->sum('amount');

        return view('report.index', compact('displayMonth', 'month', 'year', 'dateFrom', 'dateTo', 'filterType', 'incomes', 'expenses', 'recurringExpenses', 'totalIncome', 'totalExpense', 'totalRecurring'));
    }
}
