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
<<<<<<< HEAD
    private User $user;
=======
    private User $customer;

>>>>>>> origin/main

    protected function setUp(): void
    {
        parent::setUp();


        $this->seed(ProductSeeder::class);
<<<<<<< HEAD
        $this->milk = Product::where('slug', 'original-milk')->firstOrFail();
        $this->user = User::factory()->create();
=======
        $this->milk     = Product::where('slug', 'original-milk')->firstOrFail();
        $this->customer = User::factory()->create();
>>>>>>> origin/main
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

<<<<<<< HEAD
    private function makeOrder(): Order
    {
        return Order::create([
            'user_id'        => $this->user->id,
            'customer_name'  => 'Budi',
            'customer_phone' => '081234567890',
            'subtotal'       => 12000,
            'grand_total'    => 12000,
        ]);
    }

    public function test_add_to_cart_stores_product_in_session(): void
    {
        $this->actingAs($this->user)
=======

    public function test_add_to_cart_stores_product_in_session(): void
    {
        $this->actingAs($this->customer)
>>>>>>> origin/main
            ->from('/products')
            ->post('/cart', ['product_id' => $this->milk->id, 'quantity' => 2])
            ->assertRedirect(url('/products#product-' . $this->milk->id))
            ->assertSessionHas('lumeria_cart', [$this->milk->id => 2])
            ->assertSessionHas('cart_link', true);
    }

<<<<<<< HEAD
    public function test_logged_in_user_can_checkout_with_cash(): void
    {
        $this->actingAs($this->user)
            ->withSession(['lumeria_cart' => [$this->milk->id => 2]])
=======

    public function test_guest_cannot_add_to_cart_or_checkout(): void
    {
        $this->post('/cart', ['product_id' => $this->milk->id, 'quantity' => 1])
            ->assertRedirect(route('login'));


        $this->withSession(['lumeria_cart' => [$this->milk->id => 2]])
>>>>>>> origin/main
            ->post('/order', $this->payload())
            ->assertRedirect(route('login'));


        $this->assertSame(0, Order::count());
    }


    public function test_customer_can_checkout_with_cash(): void
    {
        $this->actingAs($this->customer)
            ->withSession(['lumeria_cart' => [$this->milk->id => 2]])
            ->post('/order', $this->payload())
            ->assertSessionHasNoErrors();


        $order = Order::firstOrFail();

<<<<<<< HEAD
        $this->assertEquals($this->user->id, $order->user_id);
=======

        $this->assertSame($this->customer->id, $order->user_id);
>>>>>>> origin/main
        $this->assertSame('cash', $order->payment_method);
        $this->assertSame(24000, $order->grand_total);
        $this->assertCount(1, $order->items);
        $this->assertSame(12000, $order->items->first()->price);
    }


    public function test_paylater_checkout_is_calculated_on_the_server(): void
    {
<<<<<<< HEAD
        $this->actingAs($this->user)
=======
        $this->actingAs($this->customer)
>>>>>>> origin/main
            ->withSession(['lumeria_cart' => [$this->milk->id => 3]])
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
<<<<<<< HEAD
        $this->actingAs($this->user)
=======
        $this->actingAs($this->customer)
>>>>>>> origin/main
            ->withSession(['lumeria_cart' => [$this->milk->id => 1]])
            ->post('/order', $this->payload(['payment_method' => 'paylater', 'tenor_months' => 12]))
            ->assertSessionHasErrors('tenor_months');


        $this->assertSame(0, Order::count());
    }

<<<<<<< HEAD
    public function test_guest_cannot_open_an_order_and_is_sent_to_login(): void
=======

    public function test_guest_is_sent_to_login_and_strangers_are_forbidden_on_an_unowned_order(): void
>>>>>>> origin/main
    {
        $order = $this->makeOrder();

<<<<<<< HEAD
        $this->get(route('order.show', $order))->assertRedirect('/login');
=======

        $this->get(route('order.show', $order))->assertRedirect(route('login'));


        $this->actingAs($this->customer)->get(route('order.show', $order))->assertForbidden();
>>>>>>> origin/main
    }


    public function test_other_user_cannot_view_someone_elses_order(): void
    {
        $order = $this->makeOrder();

<<<<<<< HEAD
        $this->actingAs(User::factory()->create())
            ->get(route('order.show', $order))
            ->assertForbidden();
=======

        $order = Order::create([
            'user_id'        => $owner->id,
            'customer_name'  => 'Budi',
            'customer_phone' => '081234567890',
            'subtotal'       => 12000,
            'grand_total'    => 12000,
        ]);


        $this->actingAs($other)->get(route('order.show', $order))->assertForbidden();
>>>>>>> origin/main
    }
}
