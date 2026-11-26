<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeding_twice_does_not_duplicate_products(): void
    {
        $this->seed(ProductSeeder::class);
        $count = Product::count();

        $this->seed(ProductSeeder::class);

        $this->assertSame($count, Product::count());
    }

    public function test_reseeding_does_not_overwrite_edits_made_by_an_admin(): void
    {
        $this->seed(ProductSeeder::class);

        Product::where('slug', 'original-milk')->update([
            'price' => 15500, 'name' => 'Original Milk Premium', 'description_id' => 'Deskripsi baru dari admin.',
            'badge' => 'promo', 'is_active' => false,
        ]);

        $this->seed(ProductSeeder::class);

        $product = Product::where('slug', 'original-milk')->firstOrFail();
        $this->assertSame(15500, $product->price);
        $this->assertSame('Original Milk Premium', $product->name);
        $this->assertSame('Deskripsi baru dari admin.', $product->description_id);
        $this->assertSame('promo', $product->badge);
        $this->assertFalse($product->is_active);
    }

    public function test_reseeding_recreates_a_missing_seed_product(): void
    {
        $this->seed(ProductSeeder::class);
        Product::where('slug', 'bola-teh')->delete();

        $this->seed(ProductSeeder::class);

        $this->assertDatabaseHas('products', ['slug' => 'bola-teh']);
    }
}
