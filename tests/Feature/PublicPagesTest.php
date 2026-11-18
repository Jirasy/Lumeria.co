<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductSeeder::class);
    }

    public function test_home_renders_in_indonesian(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Kecil, tapi berarti')
            ->assertSee('Mood booster dalam satu gigitan')
            ->assertSee('Tim Kami')
            ->assertSee('Original Milk');
    }

    public function test_home_renders_in_english(): void
    {
        $this->withCookie('locale', 'en')->get('/')
            ->assertOk()
            ->assertSee('Small, but meaningful')
            ->assertSee('A mood booster in one bite')
            ->assertSee('Our Team');
    }

    public function test_products_page_lists_all_six_products_with_prices(): void
    {
        $response = $this->get('/products')->assertOk();

        foreach ([
            'Original Milk', 'Bola Teh', 'Bola Kopi',
            'Bolu Lumer Cokelat/Keju', 'Kue Balok Lumer', 'Es Krim Lumeria Creamy',
        ] as $name) {
            $response->assertSee($name);
        }

        $response->assertSee('Rp 12.000')
            ->assertSee('Rp 15.000')
            ->assertSee('Rp 10.000')
            ->assertSee('Rp 8.000');
    }

    public function test_products_use_english_descriptions_when_locale_is_en(): void
    {
        $this->withCookie('locale', 'en')->get('/products')
            ->assertOk()
            ->assertSee('A soft milk ball with pure milky flavor')
            ->assertDontSee('Bola susu lembut dengan rasa susu murni');
    }

    public function test_navbar_has_language_switcher_links(): void
    {
        $this->get('/')
            ->assertSee('/lang/en', false)
            ->assertSee('/lang/id', false);
    }

    public function test_navbar_shows_cart_count_badge(): void
    {
        $id = Product::firstOrFail()->id;

        $this->withSession(['lumeria_cart' => [$id => 3]])
            ->get('/')
            ->assertSee('text-bg-danger">3</span>', false);
    }
}