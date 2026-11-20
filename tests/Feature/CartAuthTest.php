<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartAuthTest extends TestCase
{
    use RefreshDatabase;

    private Product $milk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductSeeder::class);
        $this->milk = Product::where('slug', 'original-milk')->firstOrFail();
    }

    public function test_guest_cannot_add_to_cart_and_is_sent_to_login_with_message(): void
    {
        $this->from('/products')
            ->post('/cart', ['product_id' => $this->milk->id])
            ->assertRedirect('/login')
            ->assertSessionHas('status', 'Silakan masuk atau daftar untuk melanjutkan.');

        $this->assertNull(session('lumeria_cart'));
    }

    public function test_guest_returns_to_the_product_page_after_logging_in(): void
    {
        $user = User::factory()->create();

        $this->from('/products')->post('/cart', ['product_id' => $this->milk->id]);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/products');
    }

    public function test_guest_sees_login_prompt_but_still_gets_the_simulator_on_order_page(): void
    {
        $this->get('/order')
            ->assertOk()
            ->assertSee('Masuk atau daftar untuk menambahkan produk')
            ->assertSee('Simulasi Paylater');
    }

    public function test_product_cards_show_login_button_to_guests_and_cart_form_to_users(): void
    {
        $this->get('/products')
            ->assertSee('Masuk untuk memesan')
            ->assertDontSee('Tambah ke Keranjang');

        $this->loginUser()->get('/products')
            ->assertSee('Tambah ke Keranjang')
            ->assertDontSee('Masuk untuk memesan');
    }

    public function test_guest_cannot_checkout_or_open_an_order(): void
    {
        $order = Order::create([
            'user_id'        => User::factory()->create()->id,
            'customer_name'  => 'Budi',
            'customer_phone' => '081234567890',
            'subtotal'       => 12000,
            'grand_total'    => 12000,
        ]);

        $this->post('/order', [])->assertRedirect('/login');
        $this->get(route('order.show', $order))->assertRedirect('/login');
    }

    public function test_stale_guest_session_cart_is_ignored(): void
    {
        $this->withSession(['lumeria_cart' => [$this->milk->id => 3]])
            ->get('/order')
            ->assertDontSee('text-bg-danger">3</span>', false);
    }
}