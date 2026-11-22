<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tampilan dashboard dan profil (layout app berbasis Bootstrap + tema Lumeria).
 * Perilaku simpan profil, ganti kata sandi, dan hapus akun diuji di ProfileTest dan Auth/PasswordUpdateTest.
 */
class AccountUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_app_layout_uses_the_lumeria_theme_and_no_vite_or_external_assets(): void
    {
        $user = User::factory()->create();

        foreach (['/dashboard', '/profile'] as $url) {
            $this->actingAs($user)->get($url)
                ->assertOk()
                ->assertSee('vendor/bootstrap/bootstrap.min.css', false)
                ->assertSee('css/lumeria.css', false)
                ->assertSee('vendor/bootstrap/bootstrap.bundle.min.js', false)
                ->assertSee('href="#main"', false)
                ->assertSee('id="main"', false)
                ->assertSee('/lang/en', false)
                ->assertSee(route('home'), false)
                ->assertDontSee('fonts.bunny.net', false)
                ->assertDontSee('/build/assets', false)
                ->assertDontSee('max-w-7xl', false);
        }
    }

    public function test_dashboard_greets_the_user_and_links_to_member_pages(): void
    {
        $user = User::factory()->create(['name' => 'Budi Santoso']);

        $this->actingAs($user)->withCookie('locale', 'id')->get('/dashboard')
            ->assertOk()
            ->assertSee('Halo, Budi Santoso')
            ->assertSee(route('orders.index'), false)
            ->assertSee(route('order'), false)
            ->assertSee(route('products'), false)
            ->assertSee(route('profile.edit'), false)
            ->assertDontSee(route('admin.dashboard'), false);

        $this->actingAs($user)->withCookie('locale', 'en')->get('/dashboard')
            ->assertSee('Hello, Budi Santoso')
            ->assertDontSee('Halo, Budi Santoso');
    }

    public function test_dashboard_shows_the_admin_panel_card_only_to_admins(): void
    {
        $this->actingAs(User::factory()->admin()->create())->get('/dashboard')
            ->assertOk()
            ->assertSee(route('admin.dashboard'), false);
    }

    public function test_profile_forms_keep_names_and_have_connected_labels(): void
    {
        $user = User::factory()->create();
        $html = $this->actingAs($user)->get('/profile')->assertOk()->getContent();

        $this->assertStringContainsString('action="' . route('profile.update') . '"', $html);
        $this->assertStringContainsString('action="' . route('password.update') . '"', $html);
        $this->assertStringContainsString('action="' . route('profile.destroy') . '"', $html);
        $this->assertStringContainsString('name="_method" value="patch"', $html);
        $this->assertStringContainsString('name="_method" value="put"', $html);
        $this->assertStringContainsString('name="_method" value="delete"', $html);

        $ids = [
            'name'                                => 'name',
            'email'                               => 'email',
            'update_password_current_password'    => 'current_password',
            'update_password_password'            => 'password',
            'update_password_password_confirmation' => 'password_confirmation',
            'delete_account_password'             => 'password',
        ];

        foreach ($ids as $id => $name) {
            $this->assertStringContainsString('<label for="' . $id . '"', $html, "Label untuk #{$id} tidak terhubung");
            $this->assertStringContainsString('id="' . $id . '"', $html);
            $this->assertStringContainsString('name="' . $name . '"', $html);
        }

        $this->assertStringContainsString('value="' . $user->email . '"', $html);
        $this->assertStringContainsString('data-bs-target="#confirm-user-deletion"', $html);
        $this->assertStringContainsString('id="confirm-user-deletion"', $html);
    }

    public function test_wrong_current_password_error_is_shown_in_the_password_form(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->withCookie('locale', 'en')
            ->from('/profile')
            ->followingRedirects()
            ->put('/password', [
                'current_password'      => 'not-the-password',
                'password'              => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertSee('id="update_password_current_password-error"', false)
            ->assertSee('aria-describedby="update_password_current_password-error"', false)
            ->assertSee('aria-invalid="true"', false)
            ->assertSee('is-invalid', false);
    }

    public function test_wrong_password_on_account_deletion_reopens_the_modal_with_an_error(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->withCookie('locale', 'en')
            ->from('/profile')
            ->followingRedirects()
            ->delete('/profile', ['password' => 'wrong-password'])
            ->assertSee('id="delete_account_password-error"', false)
            ->assertSee('new bootstrap.Modal(modalEl).show()', false);

        $this->assertModelExists($user);
    }

    public function test_saved_status_is_announced_after_updating_the_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->withCookie('locale', 'en')
            ->from('/profile')
            ->followingRedirects()
            ->patch('/profile', ['name' => 'Nama Baru', 'email' => $user->email])
            ->assertSee('role="status"', false)
            ->assertSee('Saved.')
            ->assertSee('value="Nama Baru"', false);
    }

    public function test_footer_links_logged_in_users_to_their_profile(): void
    {
        $this->actingAs(User::factory()->create())->get('/')
            ->assertOk()
            ->assertSee(route('profile.edit'), false);

        $this->get('/')->assertOk();
    }

    public function test_account_language_files_have_the_same_keys_in_both_locales(): void
    {
        $id = require lang_path('id/account.php');
        $en = require lang_path('en/account.php');

        $this->assertSame([], array_diff(array_keys($id), array_keys($en)));
        $this->assertSame([], array_diff(array_keys($en), array_keys($id)));

        foreach ($id as $key => $text) {
            $this->assertNotSame($text, $en[$key], "Terjemahan '{$key}' sama persis di id dan en");
        }
    }
}
