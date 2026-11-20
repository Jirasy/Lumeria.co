<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category', 'name', 'name_en', 'slug', 'description_id', 'description_en',
        'price', 'is_signature', 'image', 'is_active',
    ];

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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // $product->display_name: nama mengikuti bahasa aktif; jatuh ke nama utama bila terjemahan kosong.
    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'en' && filled($this->name_en)
            ? $this->name_en
            : $this->name;
    }
}