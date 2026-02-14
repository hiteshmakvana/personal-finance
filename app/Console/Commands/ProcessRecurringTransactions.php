<?php

namespace App\Console\Commands;

use App\Models\Expense;
use App\Models\Income;
use App\Models\RecurringTransactionLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Processes recurring expenses and incomes
 * Creates new transactions based on recurring rules
 */
class ProcessRecurringTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring:process {--date= : The date to process for (Y-m-d format)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process recurring expenses and incomes and create new transactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $processDate = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : Carbon::today();

        $this->info("Processing recurring transactions for: {$processDate->format('Y-m-d')}");

        DB::beginTransaction();

        try {
            $expensesProcessed = $this->processRecurringExpenses($processDate);
            $incomesProcessed = $this->processRecurringIncomes($processDate);

            DB::commit();

            $this->info("✓ Processed {$expensesProcessed} recurring expenses");
            $this->info("✓ Processed {$incomesProcessed} recurring incomes");
            $this->info('✓ Total transactions created: '.($expensesProcessed + $incomesProcessed));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error processing recurring transactions: '.$e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * Process recurring expenses for the given date
     */
    private function processRecurringExpenses(Carbon $processDate): int
    {
        $count = 0;

        $recurringExpenses = Expense::where('is_recurring', true)
            ->where(function ($query) use ($processDate) {
                $query->whereNull('recurring_end_date')
                    ->orWhere('recurring_end_date', '>=', $processDate);
            })
            ->get();

        foreach ($recurringExpenses as $expense) {
            if ($this->shouldProcessTransaction($expense, $processDate, 'expense')) {
                $this->createExpenseTransaction($expense, $processDate);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Process recurring incomes for the given date
     */
    private function processRecurringIncomes(Carbon $processDate): int
    {
        $count = 0;

        $recurringIncomes = Income::where('is_recurring', true)
            ->where(function ($query) use ($processDate) {
                $query->whereNull('recurring_end_date')
                    ->orWhere('recurring_end_date', '>=', $processDate);
            })
            ->get();

        foreach ($recurringIncomes as $income) {
            if ($this->shouldProcessTransaction($income, $processDate, 'income')) {
                $this->createIncomeTransaction($income, $processDate);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Determines if a transaction should be processed for the given date
     */
    private function shouldProcessTransaction($transaction, Carbon $processDate, string $type): bool
    {
        // Check if already processed for this date
        $alreadyProcessed = RecurringTransactionLog::where('transaction_type', $type)
            ->where('transaction_id', $transaction->id)
            ->where('processed_for_date', $processDate)
            ->exists();

        if ($alreadyProcessed) {
            return false;
        }

        // Check if the transaction matches the recurring rules
        return $this->matchesRecurringRule($transaction, $processDate);
    }

    /**
     * Checks if the given date matches the recurring rule
     */
    private function matchesRecurringRule($transaction, Carbon $processDate): bool
    {
        switch ($transaction->recurring_type) {
            case 'daily':
                return true;

            case 'weekly':
                // recurring_day: 0 = Sunday, 1 = Monday, etc.
                return $processDate->dayOfWeek == $transaction->recurring_day;

            case 'monthly':
                // recurring_day: day of month (1-31)
                return $processDate->day == $transaction->recurring_day;

            case 'yearly':
                // For yearly, we can store day as MMDD format
                return $processDate->format('md') == str_pad($transaction->recurring_day, 4, '0', STR_PAD_LEFT);

            default:
                return false;
        }
    }

    /**
     * Creates a new expense transaction
     */
    private function createExpenseTransaction(Expense $recurringExpense, Carbon $processDate): void
    {
        $newExpense = Expense::create([
            'amount' => $recurringExpense->amount,
            'category' => $recurringExpense->category,
            'date' => $processDate,
            'notes' => $recurringExpense->notes.' (Auto-generated from recurring expense)',
            'is_recurring' => false, // The generated expense is not recurring itself
        ]);

        // Log the processing
        RecurringTransactionLog::create([
            'transaction_type' => 'expense',
            'transaction_id' => $recurringExpense->id,
            'processed_for_date' => $processDate,
            'amount' => $recurringExpense->amount,
        ]);
    }

    /**
     * Creates a new income transaction
     */
    private function createIncomeTransaction(Income $recurringIncome, Carbon $processDate): void
    {
        $newIncome = Income::create([
            'amount' => $recurringIncome->amount,
            'source' => $recurringIncome->source,
            'date' => $processDate,
            'notes' => $recurringIncome->notes.' (Auto-generated from recurring income)',
            'is_recurring' => false, // The generated income is not recurring itself
        ]);

        // Log the processing
        RecurringTransactionLog::create([
            'transaction_type' => 'income',
            'transaction_id' => $recurringIncome->id,
            'processed_for_date' => $processDate,
            'amount' => $recurringIncome->amount,
        ]);
    }
}
