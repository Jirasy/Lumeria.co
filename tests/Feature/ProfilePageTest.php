<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Halaman profil bergaya marketplace: menu samping, kolom tambahan, unggah foto, dan foto di navbar.
 */
class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

    /** PNG 1x1 yang valid, tanpa butuh ekstensi GD. */
    private function png(string $name = 'foto.png', int $paddingKb = 0): UploadedFile
    {
        $bytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');

        return UploadedFile::fake()->createWithContent($name, $bytes . str_repeat("\0", $paddingKb * 1024));
    }

    /**
     * File sungguhan di disk. UploadedFile::fake() menebak tipe dari NAMA file, sedangkan aplikasi membaca ISI file,
     * jadi pemeriksaan tipe harus diuji dengan file nyata.
     */
    private function realFile(string $name, string $content): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'lum');
        file_put_contents($path, $content);

        return new UploadedFile($path, $name, null, null, true);
    }

    private function save(User $user, array $extra = [])
    {
        return $this->actingAs($user)->from('/profile')->patch('/profile', array_merge([
            'name'  => $user->name,
            'email' => $user->email,
        ], $extra));
    }

    public function test_profile_page_has_sidebar_and_marketplace_style_form(): void
    {
        $user = User::factory()->create(['gender' => 'male', 'phone' => '081234567890', 'birth_date' => '2008-05-17']);

        $html = $this->actingAs($user)->withCookie('locale', 'id')->get('/profile')->assertOk()->getContent();

        foreach (['Profil Saya', 'Akun Saya', 'Ubah Kata Sandi', 'Pilih Gambar', 'Ukuran gambar: maks. 1 MB', 'Format gambar: .JPEG, .PNG', 'Nomor Telepon', 'Jenis Kelamin', 'Tanggal Lahir'] as $text) {
            $this->assertStringContainsString($text, $html);
        }

        foreach (['href="#profil"', 'href="#kata-sandi"', 'href="#hapus-akun"', 'enctype="multipart/form-data"', route('orders.index')] as $needle) {
            $this->assertStringContainsString($needle, $html);
        }

        $this->assertStringContainsString('value="081234567890"', $html);
        $this->assertStringContainsString('value="2008-05-17"', $html);
        $this->assertMatchesRegularExpression('/id="gender_male"[^>]*checked/', $html);
        $this->assertStringContainsString('<label for="phone"', $html);
        $this->assertStringContainsString('<label for="birth_date"', $html);
        $this->assertStringContainsString('accept="image/png,image/jpeg"', $html);
    }

    public function test_extra_profile_fields_are_saved_and_phone_is_normalised(): void
    {
        $user = User::factory()->create();

        $this->save($user, ['phone' => '0812-3456 7890', 'gender' => 'female', 'birth_date' => '2008-05-17'])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();
        $this->assertSame('081234567890', $user->phone);
        $this->assertSame('female', $user->gender);
        $this->assertSame('2008-05-17', $user->birth_date->toDateString());
    }

    public function test_invalid_extra_fields_are_rejected(): void
    {
        $user = User::factory()->create();

        $this->save($user, ['phone' => 'abc', 'gender' => 'robot', 'birth_date' => now()->addDay()->toDateString()])
            ->assertSessionHasErrors(['phone', 'gender', 'birth_date']);

        $this->save($user, ['phone' => '12345'])->assertSessionHasErrors('phone');
    }

    public function test_role_cannot_be_changed_through_the_profile_form(): void
    {
        $user = User::factory()->create();

        $this->save($user, ['role' => 'admin', 'avatar_path' => 'x/y.png'])->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertSame(User::ROLE_CUSTOMER, $user->role);
        $this->assertNull($user->avatar_path);
    }

    public function test_avatar_is_stored_privately_and_navbar_links_the_photo_to_the_profile(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $this->save($user, ['avatar' => $this->png()])->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertNotNull($user->avatar_path);
        Storage::disk('local')->assertExists($user->avatar_path);

        $this->actingAs($user)->get('/')
            ->assertOk()
            ->assertSee('class="nav-avatar', false)
            ->assertSee('href="' . route('profile.edit') . '"', false)
            ->assertSee('/avatar/' . $user->id, false)
            ->assertDontSee('storage/avatars', false);
    }

    public function test_navbar_shows_initials_when_there_is_no_photo(): void
    {
        $user = User::factory()->create(['name' => 'Budi Santoso']);

        $this->actingAs($user)->get('/products')
            ->assertOk()
            ->assertSee('user-avatar-initials', false)
            ->assertSee('>BS<', false)
            ->assertSee('href="' . route('profile.edit') . '"', false);

        $this->get('/logout');
    }

    public function test_guest_navbar_has_no_avatar(): void
    {
        $this->get('/')->assertOk()->assertDontSee('nav-avatar', false);
    }

    public function test_replacing_the_avatar_deletes_the_old_file(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $this->save($user, ['avatar' => $this->png('a.png')]);
        $old = $user->fresh()->avatar_path;

        $this->save($user->fresh(), ['avatar' => $this->png('b.png')]);
        $new = $user->fresh()->avatar_path;

        $this->assertNotSame($old, $new);
        Storage::disk('local')->assertMissing($old);
        Storage::disk('local')->assertExists($new);
    }

    public function test_avatar_over_one_megabyte_or_wrong_type_is_rejected(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $png  = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');

        $bad = [
            'terlalu besar (1,5 MB)'      => $this->realFile('besar.png', $png . str_repeat("\0", 1536 * 1024)),
            'teks bernama .png'           => $this->realFile('catatan.png', 'ini bukan gambar'),
            'SVG berisi skrip'            => $this->realFile('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'),
            'SVG menyamar sebagai .png'   => $this->realFile('logo.png', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'),
            'PDF'                         => $this->realFile('dokumen.pdf', "%PDF-1.4\n%%EOF"),
            'PHP menyamar sebagai .jpg'   => $this->realFile('shell.jpg', '<?php echo 1;'),
        ];

        foreach ($bad as $label => $file) {
            $this->save($user, ['avatar' => $file]);
            $errors = session('errors');
            $this->assertTrue($errors && $errors->has('avatar'), "Seharusnya ditolak: {$label}");
        }

        // Gambar sungguhan di bawah 1 MB tetap diterima.
        $this->save($user, ['avatar' => $this->realFile('ok.png', $png)])->assertSessionHasNoErrors();

        $this->assertCount(1, Storage::disk('local')->allFiles());
    }

    public function test_avatar_can_be_removed(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $this->save($user, ['avatar' => $this->png()]);
        $path = $user->fresh()->avatar_path;

        $this->save($user->fresh(), ['remove_avatar' => '1'])->assertSessionHasNoErrors();

        $this->assertNull($user->fresh()->avatar_path);
        Storage::disk('local')->assertMissing($path);
    }

    public function test_only_the_owner_and_admins_can_open_an_avatar(): void
    {
        Storage::fake('local');
        $owner = User::factory()->create();
        $this->save($owner, ['avatar' => $this->png()]);
        $owner->refresh();

        $this->actingAs($owner)->get(route('avatar.show', $owner))
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff');

        $this->actingAs(User::factory()->admin()->create())->get(route('avatar.show', $owner))->assertOk();
        $this->actingAs(User::factory()->create())->get(route('avatar.show', $owner))->assertForbidden();
    }

    public function test_guest_is_sent_to_login_for_avatar_and_missing_avatar_is_404(): void
    {
        $user = User::factory()->create();

        $this->get(route('avatar.show', $user))->assertRedirect(route('login'));
        $this->actingAs($user)->get(route('avatar.show', $user))->assertNotFound();
    }

    public function test_deleting_the_account_removes_the_avatar_file(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $this->save($user, ['avatar' => $this->png()]);
        $path = $user->fresh()->avatar_path;

        $this->actingAs($user->fresh())->delete('/profile', ['password' => 'password'])->assertRedirect('/');

        Storage::disk('local')->assertMissing($path);
        $this->assertModelMissing($user);
    }

    public function test_the_last_admin_cannot_delete_their_own_account_from_the_profile(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->from('/profile')
            ->delete('/profile', ['password' => 'password'])
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertModelExists($admin);
        $this->assertAuthenticatedAs($admin);
    }

    public function test_an_admin_can_delete_their_own_account_when_another_admin_exists(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->admin()->create();

        $this->actingAs($admin)->delete('/profile', ['password' => 'password'])->assertRedirect('/');

        $this->assertModelMissing($admin);
    }

    public function test_profile_and_users_language_files_have_the_same_keys_in_both_locales(): void
    {
        foreach (['profile', 'users', 'products_admin'] as $file) {
            $id = require lang_path("id/{$file}.php");
            $en = require lang_path("en/{$file}.php");

            $this->assertSame([], array_diff(array_keys($id), array_keys($en)), "{$file}: key ada di id saja");
            $this->assertSame([], array_diff(array_keys($en), array_keys($id)), "{$file}: key ada di en saja");
        }
    }
}
