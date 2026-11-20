<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_untranslated_keys_leak_into_pages(): void
    {
        $urls = ['/', '/products', '/order', '/islamic-values', '/our-team', '/login', '/register'];

        foreach (['id', 'en'] as $locale) {
            foreach ($urls as $url) {
                $html = $this->withCookie('locale', $locale)->get($url)->getContent();
                $text = strip_tags(preg_replace('#<(script|style)\b.*?</\1>#si', '', $html));

                $this->assertDoesNotMatchRegularExpression(
                    '/\b(?:nav|order|products|home|common|team|islamic)\.[a-z_]+\b/',
                    $text,
                    "Kunci terjemahan mentah di {$url} ({$locale})"
                );
            }
        }
    }

    public function test_navbar_logo_file_exists(): void
    {
        $this->assertFileExists(public_path('images/lumeria-logo.png'));
    }
}