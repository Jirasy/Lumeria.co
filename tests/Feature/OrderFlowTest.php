<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFlowTest extends TestCase
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

    private function payload(array $override = []): array
    {
        return array_merge([
            'customer_name'    => 'Budi',
            'customer_phone'   => '081234567890',
            'customer_address' => 'Bandung',
            'payment_method'   => 'cash',
        ], $override);
    }

        public function test_add_to_cart_stores_product_in_session(): void
    {
        $this->from('/products')
            ->post('/cart', ['product_id' => $this->milk->id, 'quantity' => 2])
            ->assertRedirect(url('/products#product-' . $this->milk->id))
            ->assertSessionHas('lumeria_cart', [$this->milk->id => 2])
            ->assertSessionHas('cart_link', true);
    }

    public function test_guest_can_checkout_with_cash(): void
    {
        $this->withSession(['lumeria_cart' => [$this->milk->id => 2]])
            ->post('/order', $this->payload())
            ->assertSessionHasNoErrors();

        $order = Order::firstOrFail();

        $this->assertNull($order->user_id);
        $this->assertSame('cash', $order->payment_method);
        $this->assertSame(24000, $order->grand_total);
        $this->assertCount(1, $order->items);
        $this->assertSame(12000, $order->items->first()->price);
    }

    public function test_paylater_checkout_is_calculated_on_the_server(): void
    {
        $this->withSession(['lumeria_cart' => [$this->milk->id => 3]])
            ->post('/order', $this->payload(['payment_method' => 'paylater', 'tenor_months' => 3]))
            ->assertSessionHasNoErrors();

        $order = Order::firstOrFail();

        $this->assertSame(3, $order->tenor_months);
        $this->assertSame(2160, $order->margin_total);
        $this->assertSame(12720, $order->installment_amount);
        $this->assertSame(38160, $order->grand_total);
    }

    public function test_paylater_rejects_tenor_outside_config(): void
    {
        $this->withSession(['lumeria_cart' => [$this->milk->id => 1]])
            ->post('/order', $this->payload(['payment_method' => 'paylater', 'tenor_months' => 12]))
            ->assertSessionHasErrors('tenor_months');

        $this->assertSame(0, Order::count());
    }

    public function test_stranger_cannot_view_a_guest_order(): void
    {
        $order = Order::create([
            'customer_name'  => 'Budi',
            'customer_phone' => '081234567890',
            'subtotal'       => 12000,
            'grand_total'    => 12000,
        ]);

        $this->get(route('order.show', $order))->assertForbidden();
    }

    public function test_other_user_cannot_view_someone_elses_order(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $order = Order::create([
            'user_id'        => $owner->id,
            'customer_name'  => 'Budi',
            'customer_phone' => '081234567890',
            'subtotal'       => 12000,
            'grand_total'    => 12000,
        ]);

        $this->actingAs($other)->get(route('order.show', $order))->assertForbidden();
    }
}