# AI Agent Skills and Guidelines

## Project-Specific Instructions

This document contains coding standards and best practices for AI agents working on the Personal Finance App.

## Coding Standards

### General Rules

* Always generate code and comments in **English**, unless explicitly prompted otherwise.
* Add comments to **all** functions, classes and documents.
* **Never** describe the obvious.
* Give concise context on *why* the element exists and *what* it does.
* Use simple English.
* Avoid verbose descriptions.
* DO NOT create legacy/deprecated code unless prompted otherwise. Only implement backwards compatibility if prompted to do so.

### Example - Avoid Commenting the Obvious

```php
// BAD - Obvious comment
$is_valid = false;

// Check validity
if ($is_valid) {
    ...
}

// GOOD - Meaningful comment
$is_valid = $this->validator->check($data);

// Process only if validation passes to prevent invalid data insertion
if ($is_valid) {
    ...
}
```

## Code Style

### Clean Code Principles

* Follow Clean Code principles wherever possible.
* **ALWAYS implement using "exit early"** to flatten nested conditionals.
* Optimize for readability at all times.
* Strictly follow the **DRY (Don't Repeat Yourself) Principle** and prefer abstraction to duplication.

### Exit Early Pattern

```php
// GOOD - Exit early pattern
public function processExpense($expense)
{
    if (!$expense) {
        return null;
    }
    
    if ($expense->is_deleted) {
        return null;
    }
    
    if (!$this->hasPermission($expense)) {
        throw new UnauthorizedException();
    }
    
    return $this->process($expense);
}

// BAD - Nested conditionals
public function processExpense($expense)
{
    if ($expense) {
        if (!$expense->is_deleted) {
            if ($this->hasPermission($expense)) {
                return $this->process($expense);
            } else {
                throw new UnauthorizedException();
            }
        }
    }
    return null;
}
```

## Laravel-Specific Guidelines

### Eloquent ORM

* Use Eloquent relationships where appropriate
* Avoid raw SQL queries unless absolutely necessary
* Use query scopes for reusable query logic

```php
// GOOD - Using Eloquent
$expenses = Expense::where('is_recurring', true)
    ->where('recurring_end_date', '>=', now())
    ->get();

// BAD - Raw SQL
$expenses = DB::select('SELECT * FROM expenses WHERE is_recurring = 1 AND recurring_end_date >= ?', [now()]);
```

### Controller Best Practices

* Keep controllers thin - delegate business logic to services or models
* Use Form Request validation for complex validation rules
* Return appropriate HTTP status codes

```php
// GOOD - Thin controller
public function store(StoreExpenseRequest $request)
{
    $expense = $this->expenseService->create($request->validated());
    
    return redirect()
        ->route('expenses.index')
        ->with('success', 'Expense created successfully.');
}
```

### Model Best Practices

* Define `$fillable` or `$guarded` properties
* Use `$casts` for automatic type conversion
* Add relationships as methods
* Use accessors and mutators when needed

```php
class Expense extends Model
{
    protected $fillable = [
        'amount',
        'category',
        'date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
    ];
    
    /**
     * Defines relationship to user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

## Commenting Guidelines

### What to Comment

* **Function purpose**: Explain what the function does and why it exists
* **Complex logic**: Explain non-obvious algorithms or business rules
* **Edge cases**: Document special conditions or exceptions
* **External dependencies**: Note integration points with other systems

### What NOT to Comment

* **Obvious code**: Don't explain what the code literally does
* **Change history**: Don't add "changed by" or "modified on" comments (use Git)
* **Instructions to users**: Don't add "TODO" or "You can optionally" comments
* **Commented-out code**: Remove it instead (use Git history if needed)

### PHPDoc Standards

```php
/**
 * Processes recurring transactions for a specific date
 * Creates new expense/income entries based on recurring rules
 *
 * @param Carbon $processDate The date to process transactions for
 * @return int Number of transactions created
 * @throws \Exception If transaction processing fails
 */
private function processRecurringExpenses(Carbon $processDate): int
{
    // Implementation
}
```

## DDEV-Specific Guidelines

### Command Execution

* Always run commands inside DDEV container using `ddev exec --dir="/var/www/html"`
* Never modify files outside the container
* Use DDEV services (database, mail) instead of local alternatives

### Database Operations

```bash
# GOOD - Using DDEV
ddev exec --dir="/var/www/html" php artisan migrate

# BAD - Direct PHP
php artisan migrate
```

## Documentation Maintenance

### Always Update These Files

* `README.md` - Project overview and setup instructions
* `CLAUDE.md` - AI agent instructions and project details
* `AGENTS.md` - This file (coding standards)

### Update If Present

* `.github/copilot-instructions.md`
* `docs/*.md` - Any project documentation

### Migration Documentation

When creating migrations, add clear comments:

```php
/**
 * Adds recurring transaction support to expenses table
 * Allows expenses to be marked as recurring with specific frequency
 */
public function up(): void
{
    Schema::table('expenses', function (Blueprint $table) {
        $table->boolean('is_recurring')->default(false);
        $table->string('recurring_type')->nullable(); // daily, weekly, monthly, yearly
        $table->integer('recurring_day')->nullable(); // Day specification
        $table->date('recurring_end_date')->nullable();
    });
}
```

## Testing Guidelines

### Manual Testing

After making changes:
1. Run the application: `ddev start`
2. Test the modified functionality in browser
3. Check for console errors
4. Verify database changes

### Artisan Commands

```bash
# Test your command works
ddev exec --dir="/var/www/html" php artisan recurring:process

# Verify scheduled tasks
ddev exec --dir="/var/www/html" php artisan schedule:list

# Check routes
ddev exec --dir="/var/www/html" php artisan route:list
```

## Security Best Practices

* **Never commit sensitive data**: Use `.env` for secrets
* **Validate all inputs**: Use Laravel's validation
* **Sanitize output**: Use Blade's `{{ }}` syntax for automatic escaping
* **Use CSRF protection**: Laravel provides this by default
* **Authenticate routes**: Use middleware for protected routes

```php
// GOOD - Protected route
Route::middleware('auth')->group(function () {
    Route::resource('expenses', ExpenseController::class);
});

// GOOD - Validated input
$validated = $request->validate([
    'amount' => 'required|numeric|min:0',
    'category' => 'required|string|max:255',
]);
```

## Performance Considerations

* Use eager loading to prevent N+1 queries
* Index foreign keys and frequently queried columns
* Cache expensive queries when appropriate
* Use pagination for large datasets

```php
// GOOD - Eager loading
$expenses = Expense::with('user')->get();

// BAD - N+1 query problem
$expenses = Expense::all();
foreach ($expenses as $expense) {
    echo $expense->user->name; // Triggers query for each expense
}
```

## Git Commit Guidelines

* Write clear, descriptive commit messages
* Use present tense ("Add feature" not "Added feature")
* Reference issue numbers when applicable
* Keep commits focused on single changes

```
Good commit messages:
- Add recurring transaction processing command
- Fix duplicate entry prevention in expense creation
- Update documentation for AI agents

Bad commit messages:
- Fixed stuff
- Updates
- WIP
```

## Agent-Specific Workflows

### When Adding New Features

1. Review existing code patterns
2. Create necessary migrations
3. Update or create models
4. Implement controller logic
5. Update routes if needed
6. Add/update views
7. Update documentation
8. Test functionality
9. Commit with clear message

### When Fixing Bugs

1. Understand the issue
2. Locate the problematic code
3. Implement fix following existing patterns
4. Test the fix
5. Ensure no regressions
6. Update relevant documentation
7. Commit with descriptive message

### When Refactoring

1. Understand current implementation
2. Plan the refactoring
3. Make changes incrementally
4. Test after each change
5. Update documentation
6. Ensure all tests pass
7. Commit with explanation of changes

## Tool Preferences

### Command Line Operations

* Prefer specialized tools over bash commands:
  - Use `read` instead of `cat`
  - Use `edit` instead of `sed`
  - Use `write` instead of `echo >>`
  - Use `grep` (the tool) instead of `bash grep`
  - Use `glob` instead of `find`

### Database Operations

* Use Laravel migrations for schema changes
* Use Eloquent for data operations
* Use seeders for test data
* Use tinker for debugging

## Final Notes

* When in doubt, follow Laravel conventions
* Prioritize code readability over cleverness
* Test thoroughly before committing
* Keep documentation up to date
* Ask for clarification if requirements are unclear
