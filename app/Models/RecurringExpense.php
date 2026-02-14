<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringExpense extends Model
{
    protected $fillable = [
        'amount',
        'category',
        'start_date',
        'end_date',
        'type',
        'notes',
    ];
}

