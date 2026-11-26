<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IntegrityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Nama grup terjemahan buatan tim (nav, order, foodtech, dst.), dibaca dari lang/id.
     * Jangan dinamai groups(): PHPUnit sudah punya method final dengan nama itu.
     */
    private function translationGroups(): string
    {
        $skip = ['validation', 'auth', 'passwords', 'pagination', 'actions', 'http-statuses'];

        return collect(glob(lang_path('id/*.php')))
            ->map(fn ($file) => basename($file, '.php'))
            ->reject(fn ($name) => in_array($name, $skip, true))
            ->map(fn ($name) => preg_quote($name, '/'))
            ->implode('|');
    }

    private function assertNoRawKeys(string $html, string $where): void
    {
        $text = strip_tags(preg_replace('#<(script|style)\b.*?</\1>#si', '', $html));

        $this->assertDoesNotMatchRegularExpression(
            '/\b(?:' . $this->translationGroups() . ')\.[a-z_]+\b/',
            $text,
            "Kunci terjemahan mentah di {$where}"
        );
    }

    public function test_no_untranslated_keys_leak_into_guest_pages(): void
    {
        $this->seed(ProductSeeder::class);

        foreach (['id', 'en'] as $locale) {
            foreach (['/', '/products', '/products/original-milk', '/order', '/login', '/register'] as $url) {
                $html = $this->withCookie('locale', $locale)->get($url)->getContent();
                $this->assertNoRawKeys($html, "{$url} ({$locale})");
            }
        }
    }

    public function test_no_untranslated_keys_leak_into_member_pages(): void
    {
        $this->seed(ProductSeeder::class);
        $this->actingAs(User::factory()->create());

        foreach (['id', 'en'] as $locale) {
            foreach (['/', '/order'] as $url) {
                $html = $this->withCookie('locale', $locale)->get($url)->getContent();
                $this->assertNoRawKeys($html, "{$url} ({$locale}, login)");
            }
        }
    }

    public function test_navbar_logo_file_exists(): void
    {
        $this->assertFileExists(public_path('images/lumeria-logo.png'));
    }
}