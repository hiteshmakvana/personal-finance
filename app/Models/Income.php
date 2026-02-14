<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'amount',
        'source',
        'date',
        'notes',
        'is_recurring',
        'recurring_type',
        'recurring_day',
        'recurring_end_date',
    ];
}

