<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IslamicLangTest extends TestCase
{
    use RefreshDatabase;

    public function test_apply_items_have_same_keys_and_complete_fields_in_both_locales(): void
    {
        $id = trans('islamic.apply', [], 'id');
        $en = trans('islamic.apply', [], 'en');

        $this->assertSame(array_keys($id), array_keys($en));

        foreach (['id' => $id, 'en' => $en] as $locale => $items) {
            foreach ($items as $key => $item) {
                foreach (['title', 'text', 'basis'] as $field) {
                    $this->assertNotEmpty(
                        $item[$field] ?? null,
                        "islamic.apply.{$key}.{$field} kosong di locale {$locale}"
                    );
                }
            }
        }
    }

    public function test_new_apply_items_are_rendered_in_both_locales(): void
    {
        // Materi Nilai Islam hanya untuk admin.
        $this->actingAs(User::factory()->admin()->create());

        $this->get('/islamic-values')
            ->assertOk()
            ->assertSee('Transparan')
            ->assertSee('Layanan yang adil')
            ->assertSee('Keberkahan');

        $this->disableCookieEncryption()
            ->withCookie('locale', 'en')
            ->get('/islamic-values')
            ->assertOk()
            ->assertSee('Transparency')
            ->assertSee('Fair service')
            ->assertSee('Barakah (blessing)');
    }
}