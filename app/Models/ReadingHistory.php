<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'last_page',
        'total_pages',
        'is_completed',
        'last_read_at',
    ];

    protected function casts(): array
    {
        return [
            'last_page' => 'integer',
            'total_pages' => 'integer',
            'is_completed' => 'boolean',
            'last_read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
