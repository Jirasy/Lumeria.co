<?php


namespace Tests\Feature;


use App\Models\Product;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class FoodtechUiTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();


        $this->seed(ProductSeeder::class);
    }


    // ---------- Kartu produk: badge pojok + tombol ke detail ----------

    public function test_product_card_links_to_the_detail_page_by_slug(): void
    {
        $this->get('/products')
            ->assertOk()
            ->assertSee(route('products.show', 'original-milk'), false)
            ->assertSee('Lihat Detail');
    }


    public function test_corner_badge_shows_the_label_for_a_known_badge_key(): void
    {
        Product::where('slug', 'bola-kopi')->update(['badge' => 'promo']);


        $this->get('/products')
            ->assertOk()
            ->assertSee('ft-badge ft-badge-hot', false)
            ->assertSee('Promo');
    }


    public function test_corner_badge_is_localised(): void
    {
        Product::where('slug', 'bola-kopi')->update(['badge' => 'discount']);


        $this->get('/products')->assertSee('Diskon');
        $this->withCookie('locale', 'en')->get('/products')->assertSee('Discount')->assertSee('View Details');
    }


    public function test_unknown_badge_key_is_ignored(): void
    {
        Product::query()->update(['badge' => '<script>alert(1)</script>']);


        $this->get('/products')
            ->assertOk()
            ->assertDontSee('ft-badge', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }


    public function test_card_shows_login_button_to_guests_and_cart_form_to_customers(): void
    {
        $this->get('/products')
            ->assertSee('Masuk untuk memesan')
            ->assertSee('Lihat Detail')
            ->assertDontSee('Tambah ke Keranjang');


        $this->actingAs(User::factory()->create())->get('/products')
            ->assertSee('Tambah ke Keranjang')
            ->assertSee('Lihat Detail')
            ->assertDontSee('Masuk untuk memesan');
    }


    // ---------- Halaman detail produk ----------

    public function test_detail_page_shows_product_info_and_paylater_link(): void
    {
        $this->get(route('products.show', 'bola-kopi'))
            ->assertOk()
            ->assertSee('Bola Kopi')
            ->assertSee('Rp 12.000')
            ->assertSee('Bola susu rasa kopi')
            ->assertSee(route('order', ['amount' => 12000]), false);
    }


    public function test_detail_page_returns_404_for_unknown_or_inactive_product(): void
    {
        $this->get('/products/tidak-ada')->assertNotFound();


        Product::where('slug', 'bola-teh')->update(['is_active' => false]);


        $this->get(route('products.show', 'bola-teh'))->assertNotFound();
    }


    public function test_guest_sees_login_call_to_action_instead_of_add_to_cart(): void
    {
        $this->get(route('products.show', 'original-milk'))
            ->assertOk()
            ->assertSee('Masuk untuk memesan')
            ->assertDontSee('name="product_id"', false);
    }


    public function test_customer_sees_add_to_cart_form_and_returns_to_the_detail_page(): void
    {
        $product = Product::where('slug', 'original-milk')->firstOrFail();
        $url     = route('products.show', $product);


        $this->actingAs(User::factory()->create())
            ->get($url)
            ->assertOk()
            ->assertSee('name="product_id"', false)
            ->assertSee('id="product-' . $product->id . '"', false);


        $this->from($url)->post('/cart', ['product_id' => $product->id, 'quantity' => 2])
            ->assertRedirect($url . '#product-' . $product->id);
    }


    // ---------- Carousel dan kartu catering ----------

    public function test_home_has_a_swipeable_carousel_with_every_product(): void
    {
        $response = $this->get('/')
            ->assertOk()
            ->assertSee('data-ft-carousel', false)
            ->assertSee('data-ft-track', false)
            ->assertSee('data-ft-prev', false)
            ->assertSee('data-ft-next', false);


        foreach (Product::pluck('slug') as $slug) {
            $response->assertSee(route('products.show', $slug), false);
        }
    }


    public function test_home_has_the_catering_card_linking_to_order_info(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Lumeria Catering')
            ->assertSee('Pesan Catering')
            ->assertSee(route('order'), false);
    }


    public function test_catering_button_opens_whatsapp_when_the_number_is_configured(): void
    {
        config(['lumeria.contact.whatsapp' => '0812-3456-7890']);


        $this->get('/')->assertSee('https://wa.me/6281234567890?text=', false);
    }


    // ---------- Footer ----------

    public function test_footer_has_whatsapp_link_for_every_role(): void
    {
        config(['lumeria.contact.whatsapp' => '0812-3456-7890']);


        $check = fn ($response) => $response
            ->assertOk()
            ->assertSee('ft-footer', false)
            ->assertSee('https://wa.me/6281234567890', false)
            ->assertSee('rel="noopener noreferrer"', false);


        $check($this->get('/products'));
        $check($this->actingAs(User::factory()->create())->get('/products'));
        $check($this->actingAs(User::factory()->admin()->create())->get('/products'));
    }


    public function test_footer_hides_whatsapp_and_instagram_when_not_configured_or_invalid(): void
    {
        config(['lumeria.contact.whatsapp' => 'abc', 'lumeria.contact.instagram' => 'bad handle!']);


        $this->get('/products')
            ->assertOk()
            ->assertSee('ft-footer', false)
            ->assertDontSee('wa.me', false)
            ->assertDontSee('instagram.com', false);
    }


    public function test_footer_shows_instagram_when_configured(): void
    {
        config(['lumeria.contact.instagram' => '@lumeria.co']);


        $this->get('/')->assertSee('https://www.instagram.com/lumeria.co/', false);
    }


    public function test_footer_account_links_follow_the_role(): void
    {
        $this->get('/products')->assertSee(route('register'), false);


        $this->actingAs(User::factory()->create())->get('/products')
            ->assertDontSee(route('register'), false)
            ->assertSee(route('orders.index'), false);
    }


    // ---------- Pariasi bahasa ----------

    public function test_foodtech_lang_files_have_identical_keys_in_both_locales(): void
    {
        $flat = function (array $a, string $prefix = '') use (&$flat): array {
            $keys = [];
            foreach ($a as $k => $v) {
                is_array($v) ? $keys = array_merge($keys, $flat($v, $prefix . $k . '.')) : $keys[] = $prefix . $k;
            }

            return $keys;
        };


        $id = $flat(trans('foodtech', [], 'id'));
        $en = $flat(trans('foodtech', [], 'en'));
        sort($id);
        sort($en);


        $this->assertSame($id, $en);
        $this->assertNotEmpty($id);
    }
}
