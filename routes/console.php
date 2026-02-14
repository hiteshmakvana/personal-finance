<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Schedule recurring transactions processing
 * Runs daily at midnight to process recurring expenses and incomes
 */
Schedule::command('recurring:process')->daily()->at('00:01');
