<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tampilan halaman masuk dan daftar (layout guest berbasis Bootstrap + tema Lumeria).
 * Alur autentikasinya sendiri diuji di tests/Feature/Auth/*.
 */
class AuthUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_layout_uses_the_lumeria_theme_and_no_external_assets(): void
    {
        foreach (['/login', '/register', '/forgot-password'] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('vendor/bootstrap/bootstrap.min.css', false)
                ->assertSee('css/lumeria.css', false)
                ->assertSee('css/lumeria-foodtech.css', false)
                ->assertSee('btn-lumeria', false)
                ->assertSee('href="#main"', false)
                ->assertSee('id="main"', false)
                ->assertDontSee('fonts.bunny.net', false)
                ->assertDontSee('cdn.jsdelivr.net', false);
        }
    }

    public function test_login_page_links_to_register_home_and_products(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('action="' . route('login') . '"', false)
            ->assertSee('name="_token"', false)
            ->assertSee(route('register'), false)
            ->assertSee(route('home'), false)
            ->assertSee(route('products'), false)
            ->assertSee(route('password.request'), false);
    }

    public function test_register_page_links_back_to_login(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSee('action="' . route('register') . '"', false)
            ->assertSee('name="_token"', false)
            ->assertSee(route('login'), false)
            ->assertSee(route('home'), false)
            ->assertSee(route('products'), false);
    }

    public function test_login_inputs_have_connected_labels_and_keep_field_names(): void
    {
        $html = $this->get('/login')->assertOk()->getContent();

        foreach (['email' => 'email', 'password' => 'password'] as $id => $type) {
            $this->assertMatchesRegularExpression('/<label for="' . $id . '"/', $html, "Label untuk #{$id} tidak terhubung");
            $this->assertMatchesRegularExpression('/id="' . $id . '"\s+name="' . $id . '"\s+type="' . $type . '"/', $html);
        }

        $this->assertStringContainsString('<label for="remember_me"', $html);
        $this->assertStringContainsString('id="remember_me" type="checkbox"', $html);
        $this->assertStringContainsString('name="remember"', $html);
        $this->assertStringContainsString('autocomplete="username"', $html);
        $this->assertStringContainsString('autocomplete="current-password"', $html);
    }

    public function test_register_inputs_have_connected_labels_and_no_role_field(): void
    {
        $html = $this->get('/register')->assertOk()->getContent();

        foreach (['name', 'email', 'password', 'password_confirmation'] as $id) {
            $this->assertMatchesRegularExpression('/<label for="' . $id . '"/', $html, "Label untuk #{$id} tidak terhubung");
            $this->assertStringContainsString('id="' . $id . '"', $html);
            $this->assertStringContainsString('name="' . $id . '"', $html);
        }

        $this->assertStringContainsString('autocomplete="new-password"', $html);
        $this->assertStringNotContainsString('name="role"', $html, 'Form registrasi tidak boleh punya kolom role');
        $this->assertStringNotContainsString('admin', strtolower($html), 'Tidak ada petunjuk atau kolom admin di form registrasi');
    }

    public function test_pages_follow_the_active_locale(): void
    {
        $this->disableCookieEncryption()
            ->withCookie('locale', 'id')
            ->get('/login')
            ->assertSee('Selamat datang kembali')
            ->assertSee('Belum punya akun?')
            ->assertSee('Kembali ke Beranda')
            ->assertDontSee('Welcome back');

        $this->disableCookieEncryption()
            ->withCookie('locale', 'en')
            ->get('/login')
            ->assertSee('Welcome back')
            ->assertSee('Back to Home')
            ->assertDontSee('Selamat datang kembali');

        $this->disableCookieEncryption()
            ->withCookie('locale', 'id')
            ->get('/register')
            ->assertSee('Buat akun Lumeria.co')
            ->assertSee('Minimal 8 karakter.')
            ->assertSee('Konfirmasi Kata Sandi');

        $this->disableCookieEncryption()
            ->withCookie('locale', 'en')
            ->get('/register')
            ->assertSee('Create your Lumeria.co account')
            ->assertSee('At least 8 characters.')
            ->assertSee('Confirm Password');
    }

    public function test_language_switcher_marks_the_active_language(): void
    {
        $this->disableCookieEncryption()
            ->withCookie('locale', 'en')
            ->get('/login')
            ->assertSee('lang-switch', false)
            ->assertSee(route('lang.switch', 'id'), false)
            ->assertSee(route('lang.switch', 'en'), false)
            ->assertSee('class="btn btn-outline-dark active"', false)
            ->assertSee('aria-current="true"', false);
    }

    public function test_failed_login_shows_a_localised_accessible_error(): void
    {
        $user = User::factory()->create();

        $this->disableCookieEncryption()
            ->withCookie('locale', 'en')
            ->from('/login')
            ->followingRedirects()
            ->post('/login', ['email' => $user->email, 'password' => 'wrong-password'])
            ->assertSee('is-invalid', false)
            ->assertSee('aria-invalid="true"', false)
            ->assertSee('aria-describedby="email-error"', false)
            ->assertSee('id="email-error"', false)
            ->assertSee('These credentials do not match our records.')
            ->assertSee('value="' . $user->email . '"', false);

        $this->assertGuest();
    }

    public function test_register_errors_are_shown_next_to_fields_and_password_is_not_refilled(): void
    {
        $this->disableCookieEncryption()
            ->withCookie('locale', 'id')
            ->from('/register')
            ->followingRedirects()
            ->post('/register', [
                'name'                  => 'Budi',
                'email'                 => 'budi@example.com',
                'password'              => 'abc',
                'password_confirmation' => 'xyz',
            ])
            ->assertSee('id="password-error"', false)
            ->assertSee('aria-describedby="password-hint password-error"', false)
            ->assertSee('value="Budi"', false)
            ->assertSee('value="budi@example.com"', false)
            ->assertDontSee('value="abc"', false)
            ->assertDontSee('value="xyz"', false);

        $this->assertGuest();
    }

    public function test_auth_language_files_have_the_same_keys_in_both_locales(): void
    {
        $id = require lang_path('id/auth.php');
        $en = require lang_path('en/auth.php');

        $this->assertSame([], array_diff(array_keys($id), array_keys($en)), 'Key ada di id tapi tidak di en');
        $this->assertSame([], array_diff(array_keys($en), array_keys($id)), 'Key ada di en tapi tidak di id');

        foreach (['login_title', 'login_subtitle', 'register_title', 'register_subtitle', 'password_hint', 'no_account', 'have_account', 'back_home', 'browse_products'] as $key) {
            $this->assertArrayHasKey($key, $id);
            $this->assertNotSame($id[$key], $en[$key], "Terjemahan '{$key}' sama persis di id dan en");
        }
    }

    public function test_other_guest_pages_still_render_with_the_new_layout(): void
    {
        $user = User::factory()->create();

        $this->get(route('password.reset', 'token-uji'))->assertOk()->assertSee('name="token"', false);
        $this->actingAs($user)->get(route('password.confirm'))->assertOk()->assertSee('name="password"', false);
        $this->actingAs($user)->get(route('verification.notice'))->assertRedirect();
    }
}