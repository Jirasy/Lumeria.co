<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    public const CATEGORIES = [
        'bola_susu',
        'lumer_lainnya',
    ];

    public const BADGES = [
        'promo',
        'discount',
        'best_seller',
        'new',
    ];

    public const IMAGE_DISK = 'products';

    public const IMAGE_DIR = 'images/products';

    public const IMAGE_MAX_KB = 1024;

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

    // Label badge pojok kartu, atau null bila kosong / kunci tidak dikenal.
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

    /** Slug unik dari nama: "Bola Matcha" menjadi bola-matcha, lalu bola-matcha-2 bila sudah dipakai. */
    public static function uniqueSlugFor(string $name): string
    {
        $base = Str::limit(Str::slug($name), 100, '') ?: 'produk';
        $slug = $base;

        for ($i = 2; static::where('slug', $slug)->exists(); $i++) {
            $slug = $base . '-' . $i;
        }

        return $slug;
    }

    /** Hapus berkas foto hanya bila itu unggahan admin (di dalam images/products); foto statis lain tidak disentuh. */
    public function deleteImageFile(): void
    {
        if ($this->image && str_starts_with($this->image, self::IMAGE_DIR . '/')) {
            Storage::disk(self::IMAGE_DISK)->delete(basename($this->image));
        }
    }
}