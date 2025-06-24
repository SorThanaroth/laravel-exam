<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Terrain extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'location',
        'area_size',
        'price_per_day',
        'available_from',
        'available_to',
        'is_available',
        'main_image',
    ];

    protected $casts = [
        'area_size' => 'decimal:2',
        'price_per_day' => 'decimal:2',
        'available_from' => 'date',
        'available_to' => 'date',
        'is_available' => 'boolean',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(TerrainImage::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeInLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price_per_day', [$min, $max]);
    }
}