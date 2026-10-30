<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'author',
        'publisher',
        'publication_year',
        'isbn',
        'pages',
        'language',
        'shelf_location',
        'stock_total',
        'stock_available',
        'description',
        'cover_image',
        'has_ebook',
        'ebook_file',
        'is_featured',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'pages' => 'integer',
            'stock_total' => 'integer',
            'stock_available' => 'integer',
            'has_ebook' => 'boolean',
            'is_featured' => 'boolean',
            'views_count' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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

    public function readingHistories(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) ($this->reviews()->avg('rating') ?? 0), 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function isAvailable(): bool
    {
        return $this->stock_available > 0;
    }

    public function isBookmarkedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('author', 'like', "%{$term}%")
                ->orWhere('isbn', 'like', "%{$term}%")
                ->orWhere('publisher', 'like', "%{$term}%");
        });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}

