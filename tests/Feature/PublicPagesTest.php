<?php


namespace Tests\Feature;


use App\Models\Product;
use App\Models\User;
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
            ->assertDontSee('Tim Kami') // menu Our Team tidak tampil untuk tamu
            ->assertSee('Original Milk');
    }


    public function test_home_renders_in_english(): void
    {
        $this->withCookie('locale', 'en')->get('/')
            ->assertOk()
            ->assertSee('Small, but meaningful')
            ->assertSee('A mood booster in one bite')
            ->assertDontSee('Our Team'); // menu Our Team tidak tampil untuk tamu
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


        $this->actingAs(User::factory()->create())
            ->withSession(['lumeria_cart' => [$id => 3]])
            ->get('/')
            ->assertSee('text-bg-danger">3</span>', false);
    }


        public function test_public_layout_loads_bootstrap_locally(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('vendor/bootstrap/bootstrap.min.css', false)
            ->assertSee('vendor/bootstrap/bootstrap.bundle.min.js', false)
            ->assertDontSee('cdn.jsdelivr.net', false);
    }


    public function test_bootstrap_files_exist_and_are_not_empty(): void
    {
        $this->assertGreaterThan(100000, filesize(public_path('vendor/bootstrap/bootstrap.min.css')));
        $this->assertGreaterThan(50000, filesize(public_path('vendor/bootstrap/bootstrap.bundle.min.js')));
    }
}
