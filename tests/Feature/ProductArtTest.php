<?php

namespace Tests\Feature;

use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductArtTest extends TestCase
{
    use RefreshDatabase;

    private array $emoji = ['🥛', '🍵', '☕', '🍰', '🧇', '🍦', '🍬'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductSeeder::class);
    }

    public function test_products_page_uses_svg_art_instead_of_emoji(): void
    {
        $response = $this->get('/products')->assertOk();

        $this->assertSame(6, substr_count($response->getContent(), 'class="product-art"'));

        foreach ($this->emoji as $e) {
            $response->assertDontSee($e);
        }
    }

    public function test_home_hero_and_signature_cards_use_svg_art(): void
    {
        $response = $this->get('/')->assertOk()->assertSee('hero-ball', false);

        $this->assertSame(3, substr_count($response->getContent(), 'class="product-art"'));
        $response->assertDontSee('hero-emoji', false);

        foreach ($this->emoji as $e) {
            $response->assertDontSee($e);
        }
    }
}