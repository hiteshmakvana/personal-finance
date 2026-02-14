<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringTransactionLog extends Model
{
    protected $fillable = [
        'transaction_type',
        'transaction_id',
        'processed_for_date',
        'amount',
    ];

    protected $casts = [
        'processed_for_date' => 'date',
    ];
}
