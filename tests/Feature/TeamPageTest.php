<?php


namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;


class TeamPageTest extends TestCase
{
    use RefreshDatabase;


    // Our Team hanya untuk pengguna login (tamu diarahkan ke login; lihat RoleAccessTest).
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }


    private function member(array $overrides = []): array
    {
        return array_merge([
            'key' => 'sanaya', 'name' => 'Tester Foto',
            'class' => null, 'hobby' => null, 'motto' => null,
            'photo' => null, 'whatsapp' => null, 'instagram' => null, 'portfolio' => null,
        ], $overrides);
    }


    public function test_member_without_photo_file_gets_initials_placeholder(): void
    {
    config(['lumeria.team' => [$this->member([
        'key'   => 'zz-missing-photo-member',
        'photo' => 'images/team/tidak-ada.jpg',
    ])]]);


    $this->get('/our-team')
        ->assertOk()
        ->assertSee('team-photo-fallback', false)
        ->assertDontSee('class="team-photo rounded-circle', false)
        ->assertDontSee('tidak-ada.jpg', false);


        $this->get('/our-team')
            ->assertOk()
            ->assertSee('team-photo-fallback', false)
            ->assertDontSee('class="team-photo rounded-circle', false)
            ->assertDontSee('tidak-ada.jpg', false);
    }


    public function test_member_photo_is_rendered_when_the_file_exists(): void
    {
        $dir  = public_path('images/team');
        $file = $dir . '/zz-test-member.png';


        File::ensureDirectoryExists($dir);
        file_put_contents($file, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='
        ));


        try {
            config(['lumeria.team' => [$this->member(['key' => 'zz-test-member'])]]);


            $this->get('/our-team')
                ->assertOk()
                ->assertSee('images/team/zz-test-member.png', false)
                ->assertSee('alt="Foto Tester Foto"', false)
                ->assertDontSee('team-photo-fallback', false);
        } finally {
            @unlink($file);
        }
    }


    public function test_contact_buttons_use_clean_and_safe_links(): void
    {
        config(['lumeria.team' => [$this->member([
            'whatsapp'  => '0812-3456-7890',
            'instagram' => '@lumeria.co',
            'portfolio' => 'example.com/lumeria',
        ])]]);


        $this->get('/our-team')
            ->assertOk()
            ->assertSee('team-contact', false)
            ->assertSee('Hubungi')
            ->assertSee('https://wa.me/6281234567890?text=', false)
            ->assertSee('https://www.instagram.com/lumeria.co/', false)
            ->assertSee('https://example.com/lumeria', false)
            ->assertSee('rel="noopener noreferrer"', false);
    }


    public function test_contact_labels_are_localised(): void
    {
        config(['lumeria.team' => [$this->member([
            'whatsapp'  => '0812-3456-7890',
            'portfolio' => 'example.com/lumeria',
        ])]]);


        $this->get('/our-team')->assertSee('Portofolio')->assertSee('Hubungi');


        $this->withCookie('locale', 'en')->get('/our-team')
            ->assertSee('Portfolio')
            ->assertSee('Get in touch')
            ->assertDontSee('Portofolio');
    }


    public function test_invalid_contact_values_are_hidden_instead_of_rendered(): void
    {
        config(['lumeria.team' => [$this->member([
            'whatsapp'  => 'abc',
            'instagram' => 'bad handle!',
            'portfolio' => 'javascript:alert(1)',
        ])]]);


        $this->get('/our-team')
            ->assertOk()
            ->assertDontSee('team-contact', false)
            ->assertDontSee('wa.me', false)
            ->assertDontSee('instagram.com', false)
            ->assertDontSee('javascript:', false);
    }
}
