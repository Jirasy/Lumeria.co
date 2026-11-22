<?php


namespace Tests\Feature;


use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


/**
 * Tiga perspektif arahan Pak CEO: Tamu, Pelanggan login, Admin.
 */
class RoleAccessTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();


        $this->seed(ProductSeeder::class);
    }


    private function order(?User $owner = null): Order
    {
        return Order::create([
            'user_id'        => $owner?->id,
            'customer_name'  => 'Budi',
            'customer_phone' => '081234567890',
            'subtotal'       => 12000,
            'grand_total'    => 12000,
        ]);
    }


    // ---------- Tamu ----------

    public function test_guest_sees_storefront_without_team_or_member_menus(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee(route('products'), false)
            ->assertSee(route('order'), false)
            ->assertSee(route('login'), false)
            ->assertDontSee(route('team'), false)
            ->assertDontSee(route('islamic'), false)
            ->assertDontSee(route('economic'), false)
            ->assertDontSee(route('orders.index'), false)
            ->assertDontSee(route('admin.dashboard'), false);
    }


    public function test_guest_is_sent_to_login_for_member_and_admin_pages(): void
    {
        foreach (['/our-team', '/islamic-values', '/economic-discussion', '/admin', '/orders'] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }


    public function test_guest_can_browse_products_detail_and_paylater_simulator(): void
    {
        $this->get('/products')->assertOk();
        $this->get(route('products.show', 'original-milk'))->assertOk()->assertSee('Original Milk');
        $this->get('/order?amount=36000')->assertOk()->assertSee('Rp 38.160');
    }


    public function test_guest_order_page_explains_login_requirement(): void
    {
        $this->get('/order')
            ->assertOk()
            ->assertSee('Masuk dulu untuk memesan');


        $this->actingAs(User::factory()->create())->get('/order')
            ->assertDontSee('Masuk dulu untuk memesan');
    }


    public function test_guest_session_cart_is_ignored_and_guest_order_page_has_login_prompt(): void
    {
        $id = Product::firstOrFail()->id;


        $this->withSession(['lumeria_cart' => [$id => 3]])
            ->get('/order')
            ->assertOk()
            ->assertSee('Masuk atau daftar untuk menambahkan produk')
            ->assertSee('Simulasi Paylater')
            ->assertDontSee('text-bg-danger">3</span>', false)
            ->assertDontSee('name="tenor_months"', false);
    }


    public function test_guest_is_sent_to_login_for_order_detail_and_checkout(): void
    {
        $order = $this->order();


        $this->get(route('order.show', $order))->assertRedirect(route('login'));
        $this->post('/order', [])->assertRedirect(route('login'));
        $this->post('/cart', ['product_id' => 1])->assertRedirect(route('login'));
    }


    // ---------- Pelanggan ----------

    public function test_customer_gets_orders_and_team_menu_but_not_admin_menu(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/')
            ->assertOk()
            ->assertSee(route('orders.index'), false)
            ->assertSee('Pesanan Saya')
            ->assertSee(route('team'), false)
            ->assertDontSee(route('islamic'), false)
            ->assertDontSee(route('admin.dashboard'), false);
    }


    public function test_customer_can_open_team_but_is_forbidden_from_admin_pages(): void
    {
        $this->actingAs(User::factory()->create());


        $this->get('/our-team')->assertOk();
        $this->get('/islamic-values')->assertForbidden();
        $this->get('/economic-discussion')->assertForbidden();
        $this->get('/admin')->assertForbidden();
    }


    public function test_my_orders_lists_only_the_customers_own_orders(): void
    {
        $me    = User::factory()->create();
        $other = User::factory()->create();


        $mine   = $this->order($me);
        $theirs = $this->order($other);


        $this->actingAs($me)->get('/orders')
            ->assertOk()
            ->assertSee('#' . $mine->id)
            ->assertSee(route('order.show', $mine), false)
            ->assertDontSee(route('order.show', $theirs), false);
    }


    public function test_my_orders_shows_empty_state(): void
    {
        $this->actingAs(User::factory()->create())->get('/orders')
            ->assertOk()
            ->assertSee('Belum ada pesanan.');
    }


    public function test_customer_checkout_creates_an_order_linked_to_the_account(): void
    {
        $user = User::factory()->create();
        $id   = Product::where('slug', 'bola-teh')->firstOrFail()->id;


        $this->actingAs($user)
            ->withSession(['lumeria_cart' => [$id => 1]])
            ->post('/order', [
                'customer_name' => 'Budi', 'customer_phone' => '081234567890', 'payment_method' => 'cash',
            ])
            ->assertSessionHasNoErrors();


        $order = Order::firstOrFail();


        $this->assertSame($user->id, $order->user_id);


        $this->actingAs($user)->get('/orders')
            ->assertOk()
            ->assertSee('#' . $order->id)
            ->assertSee(route('order.show', $order), false);
    }


    // ---------- Admin ----------

    public function test_admin_gets_panel_and_materials_menu(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/')
            ->assertOk()
            ->assertSee(route('admin.dashboard'), false)
            ->assertSee(route('islamic'), false)
            ->assertSee(route('economic'), false)
            ->assertSee(route('team'), false);
    }


    public function test_admin_dashboard_shows_orders_and_products(): void
    {
        $order = $this->order();


        $this->actingAs(User::factory()->admin()->create())->get('/admin')
            ->assertOk()
            ->assertSee('Panel Admin')
            ->assertSee('#' . $order->id)
            ->assertSee('Original Milk')
            ->assertSee(route('islamic'), false);
    }


    public function test_admin_can_open_any_order_but_a_customer_cannot(): void
    {
        $order = $this->order(User::factory()->create());


        $this->actingAs(User::factory()->admin()->create())
            ->get(route('order.show', $order))->assertOk();


        $this->actingAs(User::factory()->create())
            ->get(route('order.show', $order))->assertForbidden();
    }


    // ---------- Login dan keamanan peran ----------

    public function test_login_redirects_by_role(): void
    {
        $customer = User::factory()->create();
        $admin    = User::factory()->admin()->create();


        $this->post('/login', ['email' => $customer->email, 'password' => 'password'])
            ->assertRedirect(route('dashboard', absolute: false));


        $this->post('/logout');


        $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard', absolute: false));
    }


    public function test_registration_cannot_assign_the_admin_role(): void
    {
        $this->post('/register', [
            'name' => 'Nakal', 'email' => 'nakal@example.com',
            'password' => 'password', 'password_confirmation' => 'password',
            'role' => 'admin',
        ]);


        $user = User::where('email', 'nakal@example.com')->firstOrFail();


        $this->assertFalse($user->isAdmin());
        $this->assertSame('customer', $user->role);
    }
}
