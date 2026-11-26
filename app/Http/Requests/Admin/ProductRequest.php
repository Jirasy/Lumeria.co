<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi tambah/ubah produk. Rute hanya bisa dibuka admin (group middleware 'admin'),
 * jadi authorize() cukup true.
 */
class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Harga boleh ditulis "12000", "12.000", atau "Rp 12.000"; dinormalkan jadi angka.
     * Isian lain yang bukan angka dibiarkan apa adanya agar gagal di validasi.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->price) && preg_match('/^(?:rp\.?\s*)?(\d{1,3}(?:[.\s]\d{3})+|\d+)$/i', trim($this->price), $m)) {
            $this->merge(['price' => preg_replace('/\D/', '', $m[1])]);
        }
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:100'],
            'category'       => ['required', Rule::in(Product::CATEGORIES)],
            'price'          => ['required', 'integer', 'min:1', 'max:10000000'],
            'description_id' => ['required', 'string', 'max:1000'],
            'description_en' => ['required', 'string', 'max:1000'],
            'badge'          => ['nullable', Rule::in(Product::BADGES)],
            'is_signature'   => ['nullable', 'boolean'],
            'is_active'      => ['nullable', 'boolean'],
            // Hanya JPEG/PNG/WebP, maks. 1 MB (SVG sengaja ditolak karena bisa memuat skrip).
            'image'          => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:' . Product::IMAGE_MAX_KB],
            'remove_image'   => ['nullable', 'boolean'],
        ];
    }
}
