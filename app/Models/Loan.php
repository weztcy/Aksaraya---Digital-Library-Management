<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_code',
        'user_id',
        'book_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'fine_amount',
        'fine_paid',
        'payment_method',
        'payment_reference',
        'fine_paid_at',
        'notes',
        'officer_id',
    ];

    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'due_date' => 'date',
            'return_date' => 'date',
            'fine_amount' => 'decimal:2',
            'fine_paid' => 'boolean',
            'fine_paid_at' => 'datetime',
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

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function getDaysLateAttribute(): int
    {
        $checkDate = $this->return_date ?? Carbon::today();
        if ($this->due_date && $checkDate->gt($this->due_date)) {
            return (int) $this->due_date->diffInDays($checkDate);
        }

        return 0;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'approved' => 'Disetujui (Siap Diambil)',
            'borrowed' => 'Sedang Dipinjam',
            'returned' => 'Sudah Dikembalikan',
            'rejected' => 'Ditolak',
            'overdue' => 'Terlambat',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
            'approved' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'borrowed' => 'bg-blue-100 text-blue-800 border-blue-200',
            'returned' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'rejected' => 'bg-slate-100 text-slate-700 border-slate-200',
            'overdue' => 'bg-rose-100 text-rose-800 border-rose-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'approved', 'borrowed', 'overdue']);
    }
}

