<?php

namespace Tests\Unit;

use App\Support\ContactLinks;
use App\Support\TeamPhoto;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ContactLinksTest extends TestCase
{
    public static function validWhatsapp(): array
    {
        return [
            'nol di depan, tanda hubung' => ['0812-3456-7890', 'https://wa.me/6281234567890'],
            'plus kode negara, spasi'    => ['+62 812 3456 7890', 'https://wa.me/6281234567890'],
            'sudah 62'                   => ['6281234567890', 'https://wa.me/6281234567890'],
            'tanpa nol'                  => ['81234567890', 'https://wa.me/6281234567890'],
            'tanda kurung'               => ['(0812) 3456 7890', 'https://wa.me/6281234567890'],
            'awalan 00'                  => ['0062 812 3456 7890', 'https://wa.me/6281234567890'],
            'link wa.me ditempel'        => ['https://wa.me/6281234567890', 'https://wa.me/6281234567890'],
            'nomor asing dengan plus'    => ['+1 415 555 2671', 'https://wa.me/14155552671'],
        ];
    }

    #[DataProvider('validWhatsapp')]
    public function test_whatsapp_numbers_are_normalised(string $input, string $expected): void
    {
        $this->assertSame($expected, ContactLinks::whatsapp($input));
    }

    public static function invalidWhatsapp(): array
    {
        return [[null], [''], ['   '], ['abc'], ['0812'], ['+0812345678'], ['1234567890123456789']];
    }

    #[DataProvider('invalidWhatsapp')]
    public function test_invalid_whatsapp_numbers_return_null(?string $input): void
    {
        $this->assertNull(ContactLinks::whatsapp($input));
    }

    public function test_whatsapp_message_is_url_encoded(): void
    {
        $this->assertSame(
            'https://wa.me/6281234567890?text=Halo%2C%20apa%20kabar%3F',
            ContactLinks::whatsapp('0812-3456-7890', '62', 'Halo, apa kabar?')
        );
    }

    public static function validInstagram(): array
    {
        return [
            'dengan @'          => ['@lumeria.co', 'lumeria.co'],
            'tanpa @'           => ['lumeria_co', 'lumeria_co'],
            'url lengkap'       => ['https://www.instagram.com/lumeria.co/?igsh=abc123', 'lumeria.co'],
            'url tanpa skema'   => ['instagram.com/lumeria.co', 'lumeria.co'],
        ];
    }

    #[DataProvider('validInstagram')]
    public function test_instagram_handles_are_normalised(string $input, string $handle): void
    {
        $this->assertSame(
            ['handle' => $handle, 'url' => 'https://www.instagram.com/' . $handle . '/'],
            ContactLinks::instagram($input)
        );
    }

    public static function invalidInstagram(): array
    {
        return [
            [null], [''], ['@'], ['bad handle!'], [str_repeat('a', 31)],
            ['https://instagram.com/p/ABC123/'],
            ['https://evil.example.com/lumeria'],
        ];
    }

    #[DataProvider('invalidInstagram')]
    public function test_invalid_instagram_values_return_null(?string $input): void
    {
        $this->assertNull(ContactLinks::instagram($input));
    }

    public static function urls(): array
    {
        return [
            'tanpa skema'            => ['example.com/lumeria', 'https://example.com/lumeria'],
            'http dipertahankan'     => ['http://example.com', 'http://example.com'],
            'github'                 => ['https://github.com/S-and-why', 'https://github.com/S-and-why'],
            'domain dengan port'     => ['example.com:8080/a', 'https://example.com:8080/a'],
            'javascript ditolak'     => ['javascript:alert(1)', null],
            'data ditolak'           => ['data:text/html,x', null],
            'ftp ditolak'            => ['ftp://example.com/file', null],
            'kredensial ditolak'     => ['https://user:pass@example.com', null],
            'bukan url'              => ['not a url', null],
            'tanpa titik di host'    => ['localhost:3000', null],
            'kosong'                 => ['', null],
        ];
    }

    #[DataProvider('urls')]
    public function test_portfolio_urls_are_validated(string $input, ?string $expected): void
    {
        $this->assertSame($expected, ContactLinks::url($input));
    }

    public function test_team_photo_resolves_only_existing_files_inside_public(): void
    {
        $root = sys_get_temp_dir() . '/lumeria_photo_' . uniqid();
        mkdir($root . '/images/team', 0777, true);
        file_put_contents($root . '/images/team/sanaya.jpg', 'x');
        file_put_contents($root . '/images/team/foto-lain.PNG', 'x');

        try {
            $this->assertSame('images/team/sanaya.jpg', TeamPhoto::resolve('sanaya', null, $root));
            $this->assertSame('images/team/foto-lain.PNG', TeamPhoto::resolve('sanaya', 'images/team/foto-lain.PNG', $root));
            $this->assertNull(TeamPhoto::resolve('jimny', null, $root), 'file belum ada -> null');
            $this->assertNull(TeamPhoto::resolve('x', '../../.env.png', $root), 'path traversal ditolak');
            $this->assertNull(TeamPhoto::resolve('x', 'images/team/sanaya.php', $root), 'ekstensi bukan gambar ditolak');
        } finally {
            array_map('unlink', glob($root . '/images/team/*'));
            rmdir($root . '/images/team');
            rmdir($root . '/images');
            rmdir($root);
        }
    }
}
