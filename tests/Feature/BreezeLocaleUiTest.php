<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BreezeLocaleUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_has_switcher_and_brand(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('/lang/en', false)
            ->assertSee('/lang/id', false)
            ->assertSee('Lumeria.co');
    }

    public function test_login_page_follows_locale(): void
    {
        $this->withCookie('locale', 'id')->get('/login')->assertSee('Kata Sandi');
        $this->withCookie('locale', 'en')->get('/login')->assertSee('Password');
    }

    public function test_dashboard_has_switcher_and_link_to_public_home(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('/lang/en', false)
            ->assertSee(route('home'), false);
    }
}