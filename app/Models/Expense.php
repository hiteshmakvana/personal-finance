<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'amount',
        'category',
        'date',
        'notes',
        'is_recurring',
        'recurring_type',
        'recurring_day',
        'recurring_end_date',
    ];
}

