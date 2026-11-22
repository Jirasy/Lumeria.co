<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $fillable = [
        'category', 'name', 'slug', 'description_id', 'description_en',
        'price', 'is_signature', 'badge', 'image', 'is_active',
    ];


    // Kunci badge yang dikenali kartu produk; label-nya ada di lang/*/foodtech.php.
    public const BADGES = ['promo', 'discount', 'best_seller', 'new'];

    protected $casts = [
        'price'        => 'integer',
        'is_signature' => 'boolean',
        'is_active'    => 'boolean',
    ];


    // Deskripsi otomatis mengikuti bahasa aktif: $product->description
    public function getDescriptionAttribute(): string
    {
        return app()->getLocale() === 'en'
            ? $this->description_en
            : $this->description_id;
    }


    // $product->formatted_price => "Rp 12.000"
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }


    // Label badge pojok kartu, atau null bila kosong / kunci tidak dikenal.
    public function getBadgeLabelAttribute(): ?string
    {
        return in_array($this->badge, self::BADGES, true)
            ? __('foodtech.badges.' . $this->badge)
            : null;
    }

    // promo & discount tampil mencolok (hot); best_seller & new tampil lembut (soft).
    public function getBadgeToneAttribute(): string
    {
        return in_array($this->badge, ['promo', 'discount'], true) ? 'hot' : 'soft';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
