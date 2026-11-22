<?php


return [
    'paylater' => [
        'tenors'      => [1, 2, 3],
        'margin_rate' => 2.0, // persen per bulan, flat
    ],


    'contact' => [
        // Dipakai untuk nomor WhatsApp yang ditulis diawali 0 (0812... menjadi 62812...).
        'country_code' => '62',

        // Kontak toko untuk footer, kartu catering, dan halaman detail produk.
        // Isi di .env (LUMERIA_WHATSAPP=0812-3456-7890). Kosong/tidak valid = tombolnya otomatis disembunyikan.
        'whatsapp'  => env('LUMERIA_WHATSAPP'),
        'instagram' => env('LUMERIA_INSTAGRAM'), // '@username' atau URL profil
    ],

    // Akun admin awal untuk seeder (php artisan db:seed). Dua-duanya wajib diisi agar akun dibuat.
    'admin' => [
        'name'     => env('LUMERIA_ADMIN_NAME', 'Admin Lumeria'),
        'email'    => env('LUMERIA_ADMIN_EMAIL'),
        'password' => env('LUMERIA_ADMIN_PASSWORD'),
    ],


    /*
     | Data anggota tim (halaman Our Team).
     |
     | Semua kolom selain key & name opsional. Biarkan null untuk menyembunyikan barisnya.
     |
     |   photo     Biarkan null. Foto otomatis dicari di public/images/team/<key>.webp|jpg|jpeg|png
     |             (mis. public/images/team/sanaya.jpg). Isi hanya jika nama file berbeda,
     |             mis. 'images/team/foto-sanaya.png' (relatif terhadap public/).
     |   whatsapp  Nomor apa adanya: '0812-3456-7890' atau '+62 812 3456 7890' (otomatis jadi link wa.me).
     |   instagram '@username' atau URL profil Instagram.
     |   portfolio URL portofolio / GitHub / situs pribadi (tanpa https:// pun boleh).
     |
     | Teks peran, fokus, dan bio singkat ada di lang/id/team.php dan lang/en/team.php.
     */
    'team' => [
        [
            'key' => 'sanaya', 'name' => 'Muhammad Sanaya Algifari Suryatman',
            'class' => null, 'hobby' => null, 'motto' => null,
            'photo' => null, 'whatsapp' => null, 'instagram' => null, 'portfolio' => null,
        ],
        [
            'key' => 'aliful', 'name' => 'Aliful Daffa Imtiyaz Munawar',
            'class' => null, 'hobby' => null, 'motto' => null,
            'photo' => null, 'whatsapp' => null, 'instagram' => null, 'portfolio' => null,
        ],
        [
            'key' => 'jimny', 'name' => 'Jimny Al Ramadhan Syahnendi',
            'class' => null, 'hobby' => null, 'motto' => null,
            'photo' => null, 'whatsapp' => '+62 895-7082-91000', 'instagram' => null, 'portfolio' => null,
        ],
        [
            'key' => 'alfatih', 'name' => 'Alfatih Mahesa Tora Suwigyo',
            'class' => null, 'hobby' => null, 'motto' => null,
            'photo' => null, 'whatsapp' => null, 'instagram' => null, 'portfolio' => null,
        ],
    ],
];
