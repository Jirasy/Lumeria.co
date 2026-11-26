<?php

namespace Tests\Feature;

use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_layout_links_the_theme_stylesheet(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('css/lumeria.css', false)
            ->assertSee('vendor/bootstrap/bootstrap.min.css', false)
            ->assertDontSee('cdn.jsdelivr.net', false);
    }

    public function test_theme_stylesheet_exists_and_defines_the_palette_tokens(): void
    {
        $path = public_path('css/lumeria.css');

        $this->assertFileExists($path);

        $css = file_get_contents($path);

        foreach (['--lumeria:', '--lumeria-accent:', '--lumeria-gold:', '--lumeria-surface:', '--lumeria-surface-soft:', '--lumeria-ink:'] as $token) {
            $this->assertStringContainsString($token, $css, "Token {$token} hilang dari public/css/lumeria.css");
        }

        $this->assertStringNotContainsString('http', $css, 'CSS tema tidak boleh memuat sumber luar');
    }

    public function test_signature_badge_uses_the_brand_accent_class(): void
    {
        $this->seed(ProductSeeder::class);

        $this->get('/')->assertOk()->assertSee('badge-lumeria', false);
    }
}
