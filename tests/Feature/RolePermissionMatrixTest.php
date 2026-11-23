<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Ringkasan hak akses tiga peran dalam satu tabel:
 *  - Tamu      : hanya melihat (etalase, detail produk, info pemesanan); tidak bisa memesan.
 *  - Pelanggan : melihat dan memesan, Pesanan Saya, profil.
 *  - Admin     : semua di atas + panel, kelola pengguna (CRUD), Our Team, materi.
 */
class RolePermissionMatrixTest extends TestCase
{
    use RefreshDatabase;

    private const OK = 200;
    private const LOGIN = 'login';
    private const FORBIDDEN = 403;

    /** @return array<string, array{0: string, 1: mixed, 2: mixed, 3: mixed}> [url, tamu, pelanggan, admin] */
    public static function pages(): array
    {
        return [
            'beranda'          => ['/', self::OK, self::OK, self::OK],
            'produk'           => ['/products', self::OK, self::OK, self::OK],
            'info pemesanan'   => ['/order', self::OK, self::OK, self::OK],
            'login'            => ['/login', self::OK, 302, 302],
            'pesanan saya'     => ['/orders', self::LOGIN, self::OK, self::OK],
            'dashboard'        => ['/dashboard', self::LOGIN, self::OK, self::OK],
            'profil'           => ['/profile', self::LOGIN, self::OK, self::OK],
            'our team'         => ['/our-team', self::LOGIN, self::FORBIDDEN, self::OK],
            'panel admin'      => ['/admin', self::LOGIN, self::FORBIDDEN, self::OK],
            'daftar pengguna'  => ['/admin/users', self::LOGIN, self::FORBIDDEN, self::OK],
            'tambah pengguna'  => ['/admin/users/create', self::LOGIN, self::FORBIDDEN, self::OK],
            'nilai islam'      => ['/islamic-values', self::LOGIN, self::FORBIDDEN, self::OK],
            'diskusi ekonomi'  => ['/economic-discussion', self::LOGIN, self::FORBIDDEN, self::OK],
        ];
    }

    /** @dataProvider pages */
    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_access_by_role(string $url, mixed $guest, mixed $customer, mixed $admin): void
    {
        $check = function ($response, mixed $expected) use ($url) {
            $expected === self::LOGIN
                ? $response->assertRedirect(route('login'))
                : $response->assertStatus($expected);
        };

        $check($this->get($url), $guest);
        $check($this->actingAs(User::factory()->create())->get($url), $customer);
        $check($this->actingAs(User::factory()->admin()->create())->get($url), $admin);
    }

    public function test_guest_can_view_a_product_but_cannot_order(): void
    {
        $this->seed(\Database\Seeders\ProductSeeder::class);
        $product = Product::first();

        $this->get(route('products.show', $product))->assertOk();

        $this->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1])->assertRedirect(route('login'));
        $this->post(route('order.store'), [])->assertRedirect(route('login'));
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_customer_can_add_to_cart_after_logging_in(): void
    {
        $this->seed(\Database\Seeders\ProductSeeder::class);
        $product = Product::first();

        $this->actingAs(User::factory()->create())
            ->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1])
            ->assertSessionHasNoErrors()
            ->assertRedirect();
    }

    public function test_only_admin_pages_expose_user_management_in_the_menu(): void
    {
        $this->get('/')->assertDontSee(route('admin.users.index'), false)->assertDontSee(route('team'), false);
        $this->actingAs(User::factory()->create())->get('/')->assertDontSee(route('admin.users.index'), false)->assertDontSee(route('team'), false);
        $this->actingAs(User::factory()->admin()->create())->get('/')->assertSee(route('admin.users.index'), false)->assertSee(route('team'), false);
    }
}
