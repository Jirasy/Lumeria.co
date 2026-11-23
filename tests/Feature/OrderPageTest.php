<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPageTest extends TestCase
{
    use RefreshDatabase;

    private Product $milk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductSeeder::class);
        $this->milk = Product::where('slug', 'original-milk')->firstOrFail();
        config(['lumeria.paylater.margin_rate' => 2.0]);
    }

    private function makeOrder(array $override = []): Order
    {
        $order = Order::create(array_merge([
            'customer_name'      => 'Budi',
            'customer_phone'     => '081234567890',
            'subtotal'           => 36000,
            'payment_method'     => 'paylater',
            'tenor_months'       => 3,
            'margin_rate'        => 2.0,
            'margin_total'       => 2160,
            'installment_amount' => 12720,
            'grand_total'        => 38160,
        ], $override));

        $order->items()->create([
            'product_id'   => $this->milk->id,
            'product_name' => 'Original Milk',
            'price'        => 12000,
            'quantity'     => 3,
            'line_total'   => 36000,
        ]);

        return $order;
    }

    public function test_empty_cart_shows_message_and_link_to_products(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/order')
            ->assertOk()
            ->assertSee('Keranjang kamu masih kosong.')
            ->assertSee(route('products'), false);
    }

    public function test_cart_lists_items_and_subtotal(): void
    {
        $this->actingAs(User::factory()->create())
            ->withSession(['lumeria_cart' => [$this->milk->id => 2]])
            ->get('/order')
            ->assertOk()
            ->assertSee('Original Milk')
            ->assertSee('Rp 24.000');
    }

    public function test_cart_shows_all_paylater_plans_with_radio_per_tenor(): void
    {
        // Pokok 24.000: tenor 3 => margin 1.440, total 25.440, cicilan 8.480
        $this->actingAs(User::factory()->create())
            ->withSession(['lumeria_cart' => [$this->milk->id => 2]])
            ->get('/order')
            ->assertSee('name="tenor_months"', false)
            ->assertSee('Rp 25.440')
            ->assertSee('Rp 8.480');
    }

    public function test_simulator_shows_flat_margin_plans(): void
    {
        // Pokok 36.000: 1 bln => 36.720 | 2 bln => 37.440 | 3 bln => 38.160, cicilan 12.720, margin 2.160
        $this->get('/order?amount=36000')
            ->assertOk()
            ->assertSee('Rp 36.720')
            ->assertSee('Rp 37.440')
            ->assertSee('Rp 38.160')
            ->assertSee('Rp 12.720')
            ->assertSee('Rp 2.160');
    }

    public function test_simulator_ignores_amount_below_minimum(): void
    {
        $this->get('/order?amount=5')
            ->assertOk()
            ->assertDontSee('Hasil simulasi untuk');
    }

    public function test_order_page_is_available_in_english(): void
    {
        $this->actingAs(User::factory()->create())
            ->withCookie('locale', 'en')
            ->get('/order')
            ->assertOk()
            ->assertSee('Your cart is empty.');
    }

    public function test_owner_sees_paylater_order_detail(): void
    {
        $owner = User::factory()->create();
        $order = $this->makeOrder(['user_id' => $owner->id]);

        $this->actingAs($owner)->get(route('order.show', $order))
            ->assertOk()
            ->assertSee('Budi')
            ->assertSee('Rp 38.160')
            ->assertSee('Rp 12.720')
            ->assertSee('Cicilan ke-3');
    }

<<<<<<< HEAD

=======
>>>>>>> origin/main
    public function test_guest_cannot_open_an_order_even_with_a_stale_session_marker(): void
    {
        $order = $this->makeOrder();

        $this->withSession(['last_order_id' => $order->id])
            ->get(route('order.show', $order))
            ->assertRedirect(route('login'));
    }
}