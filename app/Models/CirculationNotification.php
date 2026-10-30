<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CirculationNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'user_id',
        'channel',
        'type',
        'recipient',
        'message',
        'status',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

