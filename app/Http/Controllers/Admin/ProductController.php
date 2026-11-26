<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Kelola produk (CRUD). Seluruh rute ada di group middleware ['auth', 'admin'].
 *
 * - slug dibuat otomatis dari nama dan TIDAK berubah saat diubah, agar tautan lama tetap berlaku;
 * - menghapus produk aman: order_items menyimpan salinan nama dan harga, product_id menjadi null;
 * - untuk menyembunyikan produk tanpa menghapus, nonaktifkan (is_active = false).
 */
class ProductController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request): View
    {
        $search   = trim((string) $request->query('q', ''));
        $category = $request->query('category');
        $status   = $request->query('status');

        $products = Product::query()
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search) . '%';
                $q->where(fn ($w) => $w->where('name', 'like', $like)->orWhere('slug', 'like', $like));
            })
            ->when(in_array($category, Product::CATEGORIES, true), fn ($q) => $q->where('category', $category))
            ->when($status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderBy('category')
            ->orderBy('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'search'   => $search,
            'category' => in_array($category, Product::CATEGORIES, true) ? $category : '',
            'status'   => in_array($status, ['active', 'inactive'], true) ? $status : '',
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'product' => new Product(['category' => 'bola_susu', 'is_active' => true, 'is_signature' => false]),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $this->payload($request);
        $data['slug'] = Product::uniqueSlugFor($data['name']);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request);
        }

        $product = Product::create($data);

        return redirect()->route('admin.products.index')->with('status', __('products_admin.created', ['name' => $product->name]));
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', ['product' => $product]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $this->payload($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request);
            $product->deleteImageFile();
        } elseif ($request->boolean('remove_image')) {
            $product->deleteImageFile();
            $data['image'] = null;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('status', __('products_admin.updated', ['name' => $product->name]));
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;

        $product->deleteImageFile();
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', __('products_admin.deleted', ['name' => $name]));
    }

    /** Kolom produk dari form (tanpa slug dan berkas); kotak centang yang tidak dicentang berarti false. */
    private function payload(ProductRequest $request): array
    {
        return [
            ...$request->safe()->only(['name', 'category', 'price', 'description_id', 'description_en']),
            'badge'        => $request->input('badge') ?: null,
            'is_signature' => $request->boolean('is_signature'),
            'is_active'    => $request->boolean('is_active'),
        ];
    }

    private function storeImage(ProductRequest $request): string
    {
        // store() memberi nama acak dengan ekstensi dari isi berkas, bukan dari nama unggahan.
        $file = $request->file('image')->store('', Product::IMAGE_DISK);

        return Product::IMAGE_DIR . '/' . $file;
    }
}
