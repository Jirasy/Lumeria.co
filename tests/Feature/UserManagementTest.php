<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Kelola pengguna (CRUD) hanya untuk admin.
 */
class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create(['name' => 'Admin Utama']);
    }

    private function newUserData(array $overrides = []): array
    {
        return array_merge([
            'name'                  => 'Pengguna Baru',
            'email'                 => 'baru@example.com',
            'role'                  => 'customer',
            'password'              => 'sandi-rahasia-123',
            'password_confirmation' => 'sandi-rahasia-123',
        ], $overrides);
    }

    // ---------- Hak akses ----------

    public function test_guest_is_sent_to_login_for_every_user_admin_route(): void
    {
        $target = User::factory()->create();

        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
        $this->get(route('admin.users.create'))->assertRedirect(route('login'));
        $this->post(route('admin.users.store'), $this->newUserData())->assertRedirect(route('login'));
        $this->get(route('admin.users.edit', $target))->assertRedirect(route('login'));
        $this->put(route('admin.users.update', $target), $this->newUserData())->assertRedirect(route('login'));
        $this->delete(route('admin.users.destroy', $target))->assertRedirect(route('login'));
    }

    public function test_customer_is_forbidden_from_every_user_admin_route(): void
    {
        $customer = User::factory()->create();
        $target   = User::factory()->create();
        $this->actingAs($customer);

        $this->get(route('admin.users.index'))->assertForbidden();
        $this->get(route('admin.users.create'))->assertForbidden();
        $this->post(route('admin.users.store'), $this->newUserData())->assertForbidden();
        $this->get(route('admin.users.edit', $target))->assertForbidden();
        $this->put(route('admin.users.update', $target), $this->newUserData(['role' => 'admin']))->assertForbidden();
        $this->delete(route('admin.users.destroy', $target))->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'baru@example.com']);
        $this->assertModelExists($target);
        $this->assertSame(User::ROLE_CUSTOMER, $customer->fresh()->role);
    }

    public function test_customer_does_not_see_user_management_links(): void
    {
        $this->actingAs(User::factory()->create())->get('/')
            ->assertOk()
            ->assertDontSee(route('admin.users.index'), false);

        $this->actingAs($this->admin())->get('/')
            ->assertOk()
            ->assertSee(route('admin.users.index'), false);
    }

    // ---------- Daftar ----------

    public function test_admin_sees_the_user_list(): void
    {
        $admin = $this->admin();
        User::factory()->create(['name' => 'Siti Pelanggan', 'email' => 'siti@example.com']);

        $this->actingAs($admin)->withCookie('locale', 'id')->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Kelola Pengguna')
            ->assertSee('Siti Pelanggan')
            ->assertSee('siti@example.com')
            ->assertSee('Admin Utama')
            ->assertSee('table-responsive', false);
    }

    public function test_list_can_be_searched_and_filtered_by_role(): void
    {
        $admin = $this->admin();
        User::factory()->create(['name' => 'Budi Kurniawan', 'email' => 'budi@example.com']);
        User::factory()->create(['name' => 'Citra Lestari', 'email' => 'citra@example.com']);

        $this->actingAs($admin)->get(route('admin.users.index', ['q' => 'budi']))
            ->assertSee('Budi Kurniawan')
            ->assertDontSee('Citra Lestari');

        $this->get(route('admin.users.index', ['q' => 'citra@example']))
            ->assertSee('Citra Lestari')
            ->assertDontSee('Budi Kurniawan');

        $this->get(route('admin.users.index', ['role' => 'admin']))
            ->assertSee('Admin Utama')
            ->assertDontSee('Budi Kurniawan');

        $this->get(route('admin.users.index', ['role' => 'customer']))
            ->assertSee('Budi Kurniawan')
            ->assertDontSee('admin-utama');
    }

    public function test_search_treats_percent_and_underscore_literally(): void
    {
        $admin = $this->admin();
        User::factory()->create(['name' => 'Dewi Anggraini']);

        $this->actingAs($admin)->get(route('admin.users.index', ['q' => '%']))
            ->assertDontSee('Dewi Anggraini')
            ->assertSee(__('users.empty'));
    }

    public function test_list_is_paginated(): void
    {
        $admin = $this->admin();
        User::factory()->count(14)->create();

        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk()->assertSee('page=2', false);
        $this->get(route('admin.users.index', ['page' => 2]))->assertOk();
    }

    // ---------- Tambah ----------

    public function test_admin_can_create_a_customer_and_an_admin(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.users.store'), $this->newUserData())
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status');

        $customer = User::where('email', 'baru@example.com')->firstOrFail();
        $this->assertSame(User::ROLE_CUSTOMER, $customer->role);
        $this->assertTrue(Hash::check('sandi-rahasia-123', $customer->password));
        $this->assertNotSame('sandi-rahasia-123', $customer->getRawOriginal('password'));
        $this->assertNotNull($customer->email_verified_at);

        $this->post(route('admin.users.store'), $this->newUserData(['email' => 'admin2@example.com', 'role' => 'admin']))
            ->assertRedirect(route('admin.users.index'));

        $this->assertTrue(User::where('email', 'admin2@example.com')->firstOrFail()->isAdmin());
    }

    public function test_create_validates_input(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        $this->post(route('admin.users.store'), $this->newUserData(['email' => $admin->email]))->assertSessionHasErrors('email');
        $this->post(route('admin.users.store'), $this->newUserData(['role' => 'superuser']))->assertSessionHasErrors('role');
        $this->post(route('admin.users.store'), $this->newUserData(['password' => 'pendek', 'password_confirmation' => 'pendek']))->assertSessionHasErrors('password');
        $this->post(route('admin.users.store'), $this->newUserData(['password_confirmation' => 'beda']))->assertSessionHasErrors('password');
        $this->post(route('admin.users.store'), $this->newUserData(['name' => '']))->assertSessionHasErrors('name');

        $this->assertDatabaseMissing('users', ['email' => 'baru@example.com']);
    }

    // ---------- Ubah ----------

    public function test_admin_can_edit_another_user_including_role(): void
    {
        $this->actingAs($this->admin());
        $user = User::factory()->create(['name' => 'Nama Lama', 'email' => 'lama@example.com']);
        $oldHash = $user->password;

        $this->put(route('admin.users.update', $user), [
            'name' => 'Nama Baru', 'email' => 'baru2@example.com', 'role' => 'admin',
            'password' => '', 'password_confirmation' => '',
        ])->assertRedirect(route('admin.users.index'));

        $user->refresh();
        $this->assertSame('Nama Baru', $user->name);
        $this->assertSame('baru2@example.com', $user->email);
        $this->assertTrue($user->isAdmin());
        $this->assertSame($oldHash, $user->password, 'Kata sandi kosong berarti tidak diubah');
    }

    public function test_admin_can_reset_a_user_password(): void
    {
        $this->actingAs($this->admin());
        $user = User::factory()->create();

        $this->put(route('admin.users.update', $user), [
            'name' => $user->name, 'email' => $user->email, 'role' => 'customer',
            'password' => 'kata-sandi-baru-1', 'password_confirmation' => 'kata-sandi-baru-1',
        ])->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('kata-sandi-baru-1', $user->fresh()->password));
    }

    public function test_edit_validates_unique_email_but_allows_keeping_own_email(): void
    {
        $this->actingAs($this->admin());
        $a = User::factory()->create(['email' => 'a@example.com']);
        $b = User::factory()->create(['email' => 'b@example.com']);

        $this->put(route('admin.users.update', $a), ['name' => 'A', 'email' => 'b@example.com', 'role' => 'customer'])
            ->assertSessionHasErrors('email');

        $this->put(route('admin.users.update', $b), ['name' => 'B Baru', 'email' => 'b@example.com', 'role' => 'customer'])
            ->assertSessionHasNoErrors();
    }

    public function test_admin_cannot_change_their_own_role_but_can_edit_their_own_name(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        $this->from(route('admin.users.edit', $admin))
            ->put(route('admin.users.update', $admin), ['name' => $admin->name, 'email' => $admin->email, 'role' => 'customer'])
            ->assertSessionHasErrors('role');
        $this->assertTrue($admin->fresh()->isAdmin());

        $this->put(route('admin.users.update', $admin), ['name' => 'Nama Admin Baru', 'email' => $admin->email, 'role' => 'admin'])
            ->assertSessionHasNoErrors();
        $this->assertSame('Nama Admin Baru', $admin->fresh()->name);
    }

    public function test_edit_form_locks_the_role_for_the_own_account(): void
    {
        $admin = $this->admin();
        $other = User::factory()->create();

        $this->actingAs($admin)->get(route('admin.users.edit', $admin))->assertOk()
            ->assertSee('name="role" value="admin"', false)
            ->assertSee('disabled', false);

        $this->get(route('admin.users.edit', $other))->assertOk()
            ->assertDontSee('name="role" value=', false);
    }

    // ---------- Hapus ----------

    public function test_admin_can_delete_another_user_and_orders_are_kept(): void
    {
        $this->actingAs($this->admin());
        $user  = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id, 'customer_name' => 'Pembeli', 'customer_phone' => '081234567890',
            'subtotal' => 10000, 'payment_method' => 'cash', 'grand_total' => 10000, 'status' => 'pending',
        ]);

        $this->delete(route('admin.users.destroy', $user))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status');

        $this->assertModelMissing($user);
        $this->assertNull($order->fresh()->user_id);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error');

        $this->assertModelExists($admin);
    }

    // ---------- Regresi keamanan ----------

    public function test_role_is_not_mass_assignable(): void
    {
        $user = User::create(['name' => 'X', 'email' => 'x@example.com', 'password' => 'sandi-12345678', 'role' => 'admin']);

        $this->assertSame(User::ROLE_CUSTOMER, $user->fresh()->role);
    }
}
