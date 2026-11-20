<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductLocaleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductSeeder::class);
    }

    public function test_product_names_follow_english_locale(): void
    {
        $this->withCookie('locale', 'en')->get('/products')
            ->assertOk()
            ->assertSee('Tea Milk Ball')
            ->assertSee('Coffee Milk Ball')
            ->assertDontSee('Bola Teh')
            ->assertDontSee('Bola Kopi');
    }

    public function test_product_names_stay_indonesian_by_default(): void
    {
        $this->get('/products')
            ->assertSee('Bola Teh')
            ->assertSee('Bola Kopi')
            ->assertDontSee('Tea Milk Ball');
    }

    public function test_every_product_has_complete_translations(): void
    {
        foreach (Product::all() as $product) {
            foreach (['name', 'name_en', 'description_id', 'description_en'] as $field) {
                $this->assertNotEmpty($product->{$field}, "{$product->slug}: kolom {$field} kosong");
            }
        }
    }
}