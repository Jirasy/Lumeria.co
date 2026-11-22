<?php


namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class StaticPagesTest extends TestCase
{
    use RefreshDatabase;


    // Halaman materi (Nilai Islam, Diskusi Ekonomi) hanya untuk admin; Our Team untuk pengguna login.
    private function admin(): static
    {
        return $this->actingAs(User::factory()->admin()->create());
    }


    private function customer(): static
    {
        return $this->actingAs(User::factory()->create());
    }


    public function test_islamic_page_renders_in_indonesian(): void
    {
        $this->admin()->get('/islamic-values')
            ->assertOk()
            ->assertSee('Bukti Beriman')
            ->assertSee('Muthaffifin')
            ->assertSee('Takaran pas')
            ->assertSee('Paylater: margin, bukan bunga')
            ->assertSee('Catatan derajat');
    }


    public function test_islamic_page_renders_in_english(): void
    {
        $this->admin()->withCookie('locale', 'en')->get('/islamic-values')
            ->assertOk()
            ->assertSee('Proofs of Faith')
            ->assertSee('Fair measures')
            ->assertSee('Grading note');
    }


    public function test_economic_discussion_keeps_discussion_text_structure_in_both_locales(): void
    {
        $this->admin();

        foreach (['id', 'en'] as $locale) {
            $this->withCookie('locale', $locale)->get('/economic-discussion')
                ->assertOk()
                ->assertSee('Issue')
                ->assertSee('Arguments For')
                ->assertSee('Arguments Against')
                ->assertSee('Conclusion and Recommendation')
                ->assertSee('lang="en"', false);
        }
    }


    public function test_team_page_lists_four_founders_with_roles(): void
    {
        $this->customer();

        $this->get('/our-team')
            ->assertOk()
            ->assertSee('Muhammad Sanaya Algifari Suryatman')
            ->assertSee('Aliful Daffa Imtiyaz Munawar')
            ->assertSee('Jimny Al Ramadhan Syahnendi')
            ->assertSee('Alfatih Mahesa Tora Suwigyo')
            ->assertSee('Pemasaran & Penjualan');


        $this->withCookie('locale', 'en')->get('/our-team')
            ->assertSee('Marketing & Sales');
    }


    public function test_optional_bio_fields_only_show_when_filled(): void
    {
        $this->customer();

        config(['lumeria.team' => [[
            'key' => 'sanaya', 'name' => 'Tester Satu',
            'class' => null, 'hobby' => null, 'motto' => null,
        ]]]);


        $this->get('/our-team')->assertOk()->assertDontSee('Motto');


        config(['lumeria.team.0.motto' => 'Kecil, tapi berarti']);


        $this->get('/our-team')->assertSee('Motto')->assertSee('Kecil, tapi berarti');
    }


    public function test_public_navbar_pages_return_ok_for_guests(): void
    {
        foreach (['/', '/products', '/order'] as $url) {
            $this->get($url)->assertOk();
        }
    }


    public function test_every_navbar_page_returns_ok_for_admin(): void
    {
        $this->admin();

        foreach (['/', '/products', '/order', '/islamic-values', '/economic-discussion', '/our-team', '/admin', '/orders'] as $url) {
            $this->get($url)->assertOk();
        }
    }
}
