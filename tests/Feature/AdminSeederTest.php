<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['lumeria.admin.password' => null, 'lumeria.admin.email_domain' => 'lumeria.test']);
    }

    public function test_it_creates_the_four_team_admins_with_full_names_from_the_team_config(): void
    {
        $this->seed(AdminSeeder::class);

        $admins = User::where('role', User::ROLE_ADMIN)->orderBy('email')->get();
        $this->assertCount(4, $admins);

        $this->assertSame(
            ['alfatih@lumeria.test', 'aliful@lumeria.test', 'jimny@lumeria.test', 'sanaya@lumeria.test'],
            $admins->pluck('email')->all(),
        );

        $names = $admins->pluck('name')->all();
        foreach (config('lumeria.team') as $member) {
            $this->assertContains($member['name'], $names);
        }

        $this->assertTrue($admins->every(fn (User $u) => $u->email_verified_at !== null));
    }

    public function test_it_is_idempotent(): void
    {
        $this->seed(AdminSeeder::class);
        $this->seed(AdminSeeder::class);

        $this->assertSame(4, User::count());
    }

    public function test_configured_password_is_used_and_reset_on_rerun(): void
    {
        config(['lumeria.admin.password' => 'sandi-dari-env-123']);
        $this->seed(AdminSeeder::class);

        foreach (AdminSeeder::ADMIN_KEYS as $key) {
            $this->assertTrue(Hash::check('sandi-dari-env-123', User::where('email', "{$key}@lumeria.test")->firstOrFail()->password));
        }

        config(['lumeria.admin.password' => 'sandi-baru-456']);
        $this->seed(AdminSeeder::class);

        $this->assertTrue(Hash::check('sandi-baru-456', User::where('email', 'sanaya@lumeria.test')->firstOrFail()->password));
    }

    public function test_without_configured_password_random_unique_passwords_are_generated_and_not_rotated(): void
    {
        $this->seed(AdminSeeder::class);
        $hashes = User::where('role', 'admin')->pluck('password', 'email');

        $this->assertCount(4, $hashes->unique(), 'Setiap admin mendapat kata sandi acak yang berbeda');
        foreach (['password', 'admin', '12345678', 'lumeria', 'sanaya'] as $weak) {
            $this->assertFalse(Hash::check($weak, $hashes['sanaya@lumeria.test']), "Tidak boleh memakai kata sandi bawaan '{$weak}'");
        }

        $this->seed(AdminSeeder::class);
        $this->assertSame($hashes->all(), User::where('role', 'admin')->pluck('password', 'email')->all(), 'Menjalankan ulang tidak mengganti kata sandi yang sudah ada');
    }

    public function test_it_promotes_an_existing_account_with_the_same_email_and_leaves_others_alone(): void
    {
        $existing = User::factory()->create(['email' => 'jimny@lumeria.test', 'name' => 'Lama']);
        $other    = User::factory()->create(['email' => 'pelanggan@example.com']);
        $oldHash  = $existing->password;

        $this->seed(AdminSeeder::class);

        $existing->refresh();
        $this->assertTrue($existing->isAdmin());
        $this->assertSame('Jimny Al Ramadhan Syahnendi', $existing->name);
        $this->assertSame($oldHash, $existing->password);
        $this->assertSame(User::ROLE_CUSTOMER, $other->fresh()->role);
    }

    public function test_email_domain_can_be_configured(): void
    {
        config(['lumeria.admin.email_domain' => 'tim.example.org']);

        $this->seed(AdminSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'aliful@tim.example.org', 'role' => 'admin']);
        $this->assertDatabaseMissing('users', ['email' => 'aliful@lumeria.test']);
    }

    public function test_the_main_database_seeder_runs_the_admin_seeder(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(4, User::where('role', User::ROLE_ADMIN)->count());
    }
}
