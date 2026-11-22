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

    public function test_tailwind_palette_matches_the_theme_tokens(): void
    {
        $css = file_get_contents(public_path('css/lumeria.css'));
        $tailwind = strtolower(file_get_contents(base_path('tailwind.config.js')));

        foreach (['--lumeria-surface', '--lumeria-border', '--lumeria-muted', '--lumeria-ink', '--lumeria-darker', '--lumeria-dark'] as $token) {
            $found = preg_match('/' . preg_quote($token, '/') . ':\s*(#[0-9a-fA-F]{6})\s*;/', $css, $match);

            $this->assertSame(1, $found, "Token {$token} harus berupa hex di public/css/lumeria.css");
            $this->assertStringContainsString(
                strtolower($match[1]),
                $tailwind,
                "Warna {$token} ({$match[1]}) belum ada di tailwind.config.js; samakan palet Tailwind dengan token tema."
            );
        }
    }

    public function test_signature_badge_uses_the_brand_accent_class(): void
    {
        $this->seed(ProductSeeder::class);

        $this->get('/')->assertOk()->assertSee('badge-lumeria', false);
    }
}
