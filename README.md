# Personal Finance App

A comprehensive personal finance management application built with Laravel and DDEV. Track your income, expenses, and manage recurring transactions effortlessly.

![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.3-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

## Features

- **Income & Expense Tracking**: Easily record and manage your financial transactions
- **Recurring Transactions**: Set up automatic recurring income and expenses (daily, weekly, monthly, yearly)
- **Dashboard**: View monthly summaries with income, expenses, and net balance
- **Reports**: Generate financial reports to analyze your spending patterns
- **User Authentication**: Secure login with Laravel Breeze
- **AdminLTE Theme**: Clean and responsive UI

## Tech Stack

- **Framework**: Laravel 11.x
- **PHP**: 8.3
- **Database**: MariaDB 10.11
- **Web Server**: nginx-fpm
- **Frontend**: Blade Templates with AdminLTE
- **Development**: DDEV
- **Authentication**: Laravel Breeze

## Installation

### Prerequisites

- [DDEV](https://ddev.readthedocs.io/) installed
- Docker Desktop running
- Git

### Setup Steps

1. **Clone the repository**:
   ```bash
   cd "Local Sites"
   # Your project is already cloned at: personal-finance-app
   ```

2. **Navigate to project directory**:
   ```bash
   cd personal-finance-app
   ```

3. **Start DDEV**:
   ```bash
   ddev start
   ```

4. **Install dependencies**:
   ```bash
   ddev exec --dir="/var/www/html" composer install
   ddev exec --dir="/var/www/html" npm install
   ```

5. **Set up environment**:
   ```bash
   ddev exec --dir="/var/www/html" cp .env.example .env
   ddev exec --dir="/var/www/html" php artisan key:generate
   ```

6. **Run migrations**:
   ```bash
   ddev exec --dir="/var/www/html" php artisan migrate
   ```

7. **Build assets**:
   ```bash
   ddev exec --dir="/var/www/html" npm run build
   ```

8. **Access the application**:
   - URL: https://personal-finance-app.ddev.site

## Usage

### Access the Application

Open your browser and navigate to: https://personal-finance-app.ddev.site

### Create an Account

1. Click "Register" on the homepage
2. Fill in your details
3. Verify your email (if email verification is enabled)
4. Log in to access the dashboard

### Managing Transactions

**Add Income**:
1. Navigate to "Incomes"
2. Click "Add Income"
3. Enter amount, source, date, and notes
4. Optionally mark as recurring

**Add Expense**:
1. Navigate to "Expenses"
2. Click "Add Expense"
3. Enter amount, category, date, and notes
4. Optionally mark as recurring

### Recurring Transactions

Recurring transactions automatically generate new entries based on your schedule:

- **Daily**: Creates a transaction every day
- **Weekly**: Creates a transaction on a specific day of the week (0=Sunday, 6=Saturday)
- **Monthly**: Creates a transaction on a specific day of the month (1-31)
- **Yearly**: Creates a transaction on a specific date each year

**Set up a recurring transaction**:
1. When creating/editing an income or expense, check "Is Recurring"
2. Select the recurring type (daily, weekly, monthly, yearly)
3. Set the recurring day (day of week/month)
4. Optionally set an end date

**Process recurring transactions manually**:
```bash
# Process for today
ddev exec --dir="/var/www/html" php artisan recurring:process

# Process for a specific date
ddev exec --dir="/var/www/html" php artisan recurring:process --date=2026-02-15
```

**Automatic processing**:
The system automatically processes recurring transactions daily at 00:01 via Laravel's scheduler.

## Development

### Useful DDEV Commands

```bash
# Start the development environment
ddev start

# Stop the environment
ddev stop

# SSH into the container
ddev ssh

# Run artisan commands
ddev exec --dir="/var/www/html" php artisan <command>

# Clear cache
ddev exec --dir="/var/www/html" php artisan cache:clear

# View routes
ddev exec --dir="/var/www/html" php artisan route:list

# View scheduled tasks
ddev exec --dir="/var/www/html" php artisan schedule:list

# Access database
ddev exec --dir="/var/www/html" php artisan tinker
```

### Database

Access the database:
```bash
# Via Tinker
ddev exec --dir="/var/www/html" php artisan tinker

# Via MySQL CLI
ddev mysql
```

### Running Migrations

```bash
ddev exec --dir="/var/www/html" php artisan migrate
```

### Create New Migration

```bash
ddev exec --dir="/var/www/html" php artisan make:migration create_table_name
```

## Scheduler Setup (Production)

For automatic recurring transaction processing in production, add this to your crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

This ensures the Laravel scheduler runs every minute and processes scheduled tasks.

## Project Structure

```
app/
├── Console/Commands/          # Artisan commands
├── Http/Controllers/          # Controllers
├── Models/                    # Eloquent models
database/
├── migrations/                # Database migrations
resources/
├── views/                     # Blade templates
routes/
├── web.php                    # Web routes
├── console.php                # Scheduled tasks
```

## Key Files

- `CLAUDE.md` - Detailed documentation for AI agents
- `AGENTS.md` - Coding standards and guidelines
- `.ddev/config.yaml` - DDEV configuration

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes following the coding standards in `AGENTS.md`
4. Test thoroughly
5. Submit a pull request

## License

This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues, questions, or contributions, please refer to:
- `CLAUDE.md` for detailed project documentation
- `AGENTS.md` for coding standards

## Troubleshooting

**DDEV won't start**:
- Ensure Docker Desktop is running
- Run `ddev poweroff` then `ddev start`

**Database connection issues**:
- Check `.env` file for correct database credentials
- Run `ddev restart`

**Cache issues**:
```bash
ddev exec --dir="/var/www/html" php artisan cache:clear
ddev exec --dir="/var/www/html" php artisan config:clear
ddev exec --dir="/var/www/html" php artisan view:clear
```

**Recurring transactions not processing**:
- Verify the scheduler is configured (see Scheduler Setup)
- Manually run: `ddev exec --dir="/var/www/html" php artisan recurring:process`
- Check logs: `ddev logs`
