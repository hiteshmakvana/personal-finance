<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bulk delete routes (must be before resource routes)
    Route::delete('incomes/bulk-delete', [\App\Http\Controllers\IncomeController::class, 'bulkDestroy'])->name('incomes.bulk-destroy');
    Route::delete('expenses/bulk-delete', [\App\Http\Controllers\ExpenseController::class, 'bulkDestroy'])->name('expenses.bulk-destroy');

    // Income and Expense CRUD routes
    Route::resource('incomes', \App\Http\Controllers\IncomeController::class);
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);

    // User management - admin or super-admin only
    Route::resource('users', \App\Http\Controllers\UserController::class)
        ->only(['index', 'edit', 'update', 'create', 'store'])
        ->middleware('admin.or.super');

    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
});

require __DIR__.'/auth.php';
