<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class Cart
{
    private const KEY = 'lumeria_cart';

    public function add(Product $product, int $qty = 1): void
    {
        if (! auth()->check()) {
            return;
        }

        $items = $this->raw();
        $items[$product->id] = min(99, ($items[$product->id] ?? 0) + max(1, $qty));
        $this->save($items);
    }

    public function set(Product $product, int $qty): void
    {
        if (! auth()->check()) {
            return;
        }

        $items = $this->raw();

        if ($qty < 1) {
            unset($items[$product->id]);
        } else {
            $items[$product->id] = min(99, $qty);
        }

        $this->save($items);
    }

    public function remove(Product $product): void
    {
        if (! auth()->check()) {
            return;
        }

        $items = $this->raw();
        unset($items[$product->id]);
        $this->save($items);
    }

    public function clear(): void
    {
        if (! auth()->check()) {
            return;
        }

        session()->forget(self::KEY);
    }

    /** Harga selalu diambil dari database, tidak pernah dari request. */
    public function items(): Collection
    {
        if (! auth()->check()) {
            return collect();
        }

        $raw = $this->raw();

        if (! $raw) {
            return collect();
        }

        $products = Product::active()->whereIn('id', array_keys($raw))->get()->keyBy('id');

        return collect($raw)
            ->map(function ($qty, $id) use ($products) {
                $product = $products->get($id);

                return $product ? [
                    'product'    => $product,
                    'quantity'   => $qty,
                    'line_total' => $product->price * $qty,
                ] : null;
            })
            ->filter()
            ->values();
    }

    public function subtotal(): int
    {
        if (! auth()->check()) {
            return 0;
        }

        return (int) $this->items()->sum('line_total');
    }

    /** Untuk badge navbar: murah, tanpa query. */
    public function count(): int
    {
        if (! auth()->check()) {
            return 0;
        }

        return array_sum($this->raw());
    }

    private function raw(): array
    {
        // Keranjang hanya untuk pelanggan login. Sisa keranjang sesi lama milik tamu diabaikan,
        // jadi badge navbar, tombol keranjang, dan halaman Pesan tidak menampilkannya.
        if (! auth()->check()) {
            return [];
        }

        return session(self::KEY, []);
    }

    private function save(array $items): void
    {
        if (! auth()->check()) {
            return;
        }

        session([self::KEY => $items]);
    }
}
