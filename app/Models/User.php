<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'member_number',
        'phone',
        'address',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function isAnggota(): bool
    {
        return $this->role === 'anggota';
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'petugas'], true);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function bookmarkedBooks(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'bookmarks')->withTimestamps();
    }

    public function readingHistories(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function userShelves(): HasMany
    {
        return $this->hasMany(UserShelf::class);
    }

    public function readingChallenges(): HasMany
    {
        return $this->hasMany(ReadingChallenge::class);
    }

    public function getCurrentChallenge(): ?ReadingChallenge
    {
        return $this->readingChallenges()->where('year', (int) date('Y'))->first();
    }

    public function activeLoansCount(): int
    {
        return $this->loans()
            ->whereIn('status', ['pending', 'approved', 'borrowed', 'overdue'])
            ->count();
    }
}
