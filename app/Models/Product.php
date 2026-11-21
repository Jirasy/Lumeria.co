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

    // Nama otomatis mengikuti bahasa aktif jika name_en tersedia: $product->display_name
    public function getDisplayNameAttribute(): string
    {
        if (app()->getLocale() === 'en' && filled($this->name_en)) {
            return (string) $this->name_en;
        }

        return (string) $this->name;
    }

    // Deskripsi otomatis mengikuti bahasa aktif: $product->description
    public function getDescriptionAttribute(): string
    {
        return app()->getLocale() === 'en'
            ? (string) $this->description_en
            : (string) $this->description_id;
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
}