<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserShelf extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'shelf_type',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public static function getShelfLabel(string $type): string
    {
        return match ($type) {
            'want_to_read' => 'Ingin Dibaca',
            'currently_reading' => 'Sedang Dibaca',
            'already_read' => 'Selesai Dibaca',
            default => ucfirst(str_replace('_', ' ', $type)),
        };
    }
}

