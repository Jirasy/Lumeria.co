<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const BADGES = [
        'promo',
        'discount',
        'best_seller',
        'new',
    ];

    protected $fillable = [
        'category', 'name', 'name_en', 'slug', 'description_id', 'description_en',
        'price', 'is_signature', 'badge', 'image', 'is_active',
    ];

    protected $casts = [
        'price'        => 'integer',
        'is_signature' => 'boolean',
        'is_active'    => 'boolean',
    ];

    public function getDisplayNameAttribute(): string
    {
        if (app()->getLocale() === 'en' && filled($this->name_en)) {
            return (string) $this->name_en;
        }

        return (string) $this->name;
    }

    public function getDescriptionAttribute(): string
    {
        return app()->getLocale() === 'en'
            ? (string) $this->description_en
            : (string) $this->description_id;
    }

    public function getBadgeLabelAttribute(): ?string
    {
        $badge = (string) ($this->badge ?? '');

        if (! in_array($badge, self::BADGES, true)) {
            return null;
        }

        return (string) __('foodtech.badges.' . $badge);
    }

    public function getBadgeToneAttribute(): string
    {
        return match ($this->badge) {
            'promo'       => 'hot',
            'discount'    => 'discount',
            'best_seller' => 'bestseller',
            'new'         => 'new',
            default        => 'hot',
        };
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
