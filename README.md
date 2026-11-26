<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Akun admin, peran, dan foto profil

**Peran.** Tamu hanya melihat (etalase, detail produk, info pemesanan) dan harus login atau daftar sebelum memesan.
Pelanggan bisa memesan, melihat Pesanan Saya, dan mengatur profil. Admin juga mengelola pengguna (tambah, ubah, hapus),
panel toko, **kelola produk** (tambah, ubah, nonaktifkan, hapus, dengan foto), halaman Our Team, dan materi. Peran tidak bisa diubah lewat form registrasi atau profil.

**Setelah menarik perubahan ini**, jalankan migrasi (menambah kolom profil di tabel users):

```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder   # atau: php artisan db:seed (seluruh seeder)
```

`AdminSeeder` membuat 4 admin tim: `sanaya@`, `aliful@`, `jimny@`, dan `alfatih@` diikuti `LUMERIA_ADMIN_EMAIL_DOMAIN`
(bawaan `lumeria.test`; ganti di `.env` bila perlu). Nama lengkap diambil dari `config/lumeria.php`.

- Isi `LUMERIA_ADMIN_PASSWORD` di `.env` agar keempat akun memakai kata sandi itu (diatur ulang setiap seeder dijalankan).
- Bila dikosongkan, kata sandi acak dibuat untuk akun baru dan **ditampilkan sekali** di terminal. Catat saat itu juga.
- Tidak ada kata sandi bawaan di kode karena repositori ini publik. Ganti kata sandi lewat halaman Profil setelah login pertama.
- Seeder aman dijalankan berulang; akun dicari lewat email dan tidak digandakan.

**Foto profil** disimpan di disk privat (`storage/app/private/avatars`) dan disajikan lewat rute `/avatar/{user}`
yang hanya bisa dibuka pemilik dan admin, jadi tidak perlu `php artisan storage:link`. Batas 1 MB, format JPEG atau PNG.

**Foto produk** yang diunggah admin disimpan langsung di `public/images/products` (maks. 1 MB, JPEG/PNG/WebP, nama berkas acak),
jadi juga tidak perlu `storage:link`. Folder itu di-ignore git: foto unggahan tidak ikut ter-commit dan tidak ikut terbawa saat `git pull`,
jadi salin folder tersebut sendiri bila memindahkan situs. Produk tanpa foto memakai ilustrasi bawaan.
Menghapus produk aman: pesanan lama menyimpan salinan nama dan harga. Untuk menyembunyikan saja, nonaktifkan produk.
`php artisan db:seed` hanya membuat produk bawaan yang belum ada, jadi tidak menimpa suntingan admin.

**Tampilan** memakai Bootstrap dan CSS di `public/`, jadi tidak ada langkah `npm` atau build aset.
