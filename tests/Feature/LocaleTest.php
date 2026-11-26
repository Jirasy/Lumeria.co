<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleTest extends TestCase
{
    public function test_default_locale_is_indonesian(): void
    {
        $this->get('/login');

        $this->assertSame('id', app()->getLocale());
    }

    public function test_switch_sets_cookie_and_redirects_back(): void
    {
        $this->from('/login')
            ->get('/lang/en')
            ->assertRedirect('/login')
            ->assertPlainCookie('locale', 'en');
    }

    public function test_locale_cookie_is_applied_to_requests(): void
    {
        $this->disableCookieEncryption()
            ->withCookie('locale', 'en')
            ->get('/login');

        $this->assertSame('en', app()->getLocale());
    }

    public function test_unsupported_locale_returns_404(): void
    {
        $this->get('/lang/fr')->assertNotFound();
    }

    public function test_tampered_cookie_falls_back_to_default(): void
    {
        $this->disableCookieEncryption()
            ->withCookie('locale', 'xx')
            ->get('/login');

        $this->assertSame('id', app()->getLocale());
    }
}