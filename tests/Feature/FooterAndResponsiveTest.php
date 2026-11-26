<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Footer bergaya marketplace dan dasar tata letak responsif di semua layout.
 */
class FooterAndResponsiveTest extends TestCase
{
    use RefreshDatabase;

    private function footer(string $html): string
    {
        preg_match('#<footer class="ft-footer">.*?</footer>#s', $html, $m);

        return $m[0] ?? '';
    }

    public function test_guest_footer_has_service_explore_account_and_payment_columns(): void
    {
        $html = $this->disableCookieEncryption()
            ->withCookie('locale', 'id')
            ->get('/products')
            ->assertOk()
            ->getContent();
        $footer = $this->footer($html);

        foreach (['Layanan Pelanggan', 'Jelajahi', 'Akun', 'Pembayaran', 'Cara Memesan', 'Lacak Pesanan', 'Tunai', 'Paylater', 'Cicilan 1 sampai 3 bulan', 'Bahasa:'] as $text) {
            $this->assertStringContainsString($text, $footer);
        }

        foreach ([route('order'), route('login'), route('register'), route('home'), route('products'), route('lang.switch', 'en')] as $url) {
            $this->assertStringContainsString($url, $footer);
        }

        $this->assertStringNotContainsString('<img', $footer, 'Metode pembayaran berupa teks, tanpa logo pihak ketiga');
    }

    public function test_footer_follows_the_locale_and_the_configured_tenors(): void
    {
        config(['lumeria.paylater.tenors' => [2, 4, 6]]);

        $html = $this->disableCookieEncryption()
            ->withCookie('locale', 'en')
            ->get('/')
            ->getContent();
        $footer = $this->footer($html);

        $this->assertStringContainsString('Customer Service', $footer);
        $this->assertStringContainsString('Payment', $footer);
        $this->assertStringContainsString('Installments of 2 to 6 months', $footer);
        $this->assertStringContainsString('Language:', $footer);
        $this->assertStringNotContainsString('Layanan Pelanggan', $footer);
    }

    public function test_footer_account_links_follow_the_role(): void
    {
        $customer = $this->footer($this->actingAs(User::factory()->create())->get('/')->getContent());
        $this->assertStringContainsString(route('profile.edit'), $customer);
        $this->assertStringContainsString(route('orders.index'), $customer);
        $this->assertStringNotContainsString(route('register'), $customer);
        $this->assertStringNotContainsString(route('admin.users.index'), $customer);
        $this->assertStringNotContainsString(route('admin.products.index'), $customer);
        $this->assertStringNotContainsString(route('admin.dashboard'), $customer);

        $admin = $this->footer($this->actingAs(User::factory()->admin()->create())->get('/')->getContent());
        $this->assertStringContainsString(route('admin.users.index'), $admin);
        $this->assertStringContainsString(route('admin.products.index'), $admin);
        $this->assertStringContainsString(route('admin.dashboard'), $admin);
    }

    public function test_follow_us_column_only_appears_when_a_contact_is_configured(): void
    {
        config(['lumeria.contact.whatsapp' => null, 'lumeria.contact.instagram' => null]);
        $this->assertStringNotContainsString('Ikuti Kami', $this->footer($this->get('/')->getContent()));

        config(['lumeria.contact.whatsapp' => '0812-3456-7890', 'lumeria.contact.instagram' => '@lumeria.co']);
        $html = $this->disableCookieEncryption()
            ->withCookie('locale', 'id')
            ->get('/')
            ->getContent();
        $footer = $this->footer($html);

        $this->assertStringContainsString('Ikuti Kami', $footer);
        $this->assertStringContainsString('https://wa.me/6281234567890', $footer);
        $this->assertStringContainsString('https://www.instagram.com/lumeria.co/', $footer);
        $this->assertStringContainsString('Hubungi Kami', $footer);
        $this->assertSame(substr_count($footer, 'target="_blank"'), substr_count($footer, 'rel="noopener noreferrer"'));
    }

    public function test_every_layout_declares_the_viewport_for_mobile(): void
    {
        $customer = User::factory()->create();
        $admin    = User::factory()->admin()->create();

        $pages = [
            [null, '/'], [null, '/login'], [null, '/register'],
            [$customer, '/dashboard'], [$customer, '/profile'],
            [$admin, '/admin'], [$admin, '/admin/users'], [$admin, '/admin/products'], [$admin, '/admin/products/create'],
        ];

        foreach ($pages as [$user, $url]) {
            $response = $user ? $this->actingAs($user)->get($url) : $this->get($url);
            $response->assertOk()->assertSee('<meta name="viewport" content="width=device-width, initial-scale=1">', false);
        }
    }

    public function test_wide_content_uses_responsive_containers(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get('/admin/users')->assertSee('table-responsive', false)->assertSee('stack-table', false);
        $this->get('/admin')->assertSee('table-responsive', false);
        $this->get('/profile')->assertSee('order-first order-md-last', false);
    }
}