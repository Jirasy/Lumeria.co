# Lumeria.co: tampilan foodtech + 3 perspektif (Tamu, Pelanggan, Admin)

Paket ini berisi 40 berkas (baru + ubahan) yang mengikuti struktur folder repo, ditambah `perubahan-berkas-lama.diff` untuk melihat apa yang berubah di berkas yang sudah ada.

## 1. Keputusan yang perlu dikonfirmasi ke Pak CEO

Arahan CEO belum menjawab tiga hal ini, jadi saya pilih default dan menandainya. Masing-masing bisa dibalik.

| # | Pertanyaan | Default saya | Cara membalik |
|---|---|---|---|
| 1 | Our Team untuk siapa? | Semua yang login (tamu tidak melihat) | Admin saja: pindahkan baris `/our-team` ke grup admin di `routes/web.php`, hapus `['team', 'nav.team']` untuk pelanggan di `navbar.blade.php`, ubah `actingAs` di `TeamPageTest` dan `StaticPagesTest` menjadi admin |
| 2 | Diskusi Ekonomi untuk siapa? | Admin, bersama Nilai Islam (arahan tidak menyebutnya) | Pindahkan baris `/economic-discussion` ke grup `auth` atau ke publik |
| 3 | Boleh tamu memakai keranjang dan checkout? | Tidak. Tamu hanya melihat etalase, detail produk, simulator Paylater, dan WhatsApp | Keluarkan 3 rute `cart.*` dan `order.store` dari grup `auth`, lalu pakai kembali `OrderFlowTest` lama |

Dua hal lain yang perlu dicek tim, karena isinya contoh dan bukan fakta bisnis:
- Badge **Terlaris** pada Original Milk di `ProductSeeder`. Ubah atau hapus kalau belum benar.
- Teks kartu catering di `lang/id/foodtech.php` dan `lang/en/foodtech.php`. Sesuaikan dengan layanan yang benar-benar ada.

## 2. Matriks peran

| | Tamu | Pelanggan | Admin |
|---|---|---|---|
| Beranda (kartu catering, carousel, kartu produk), Produk, Detail produk | ya | ya | ya |
| Pesan & Paylater (info + simulator) | ya, dengan catatan "masuk dulu untuk memesan" | ya | ya |
| Keranjang, checkout | tidak (diarahkan ke login) | ya | ya |
| Pesanan Saya (`/orders`) | tidak | ya | ya |
| Detail pesanan (`/order/{id}`) | hanya sesi yang baru memesan | pesanannya sendiri | semua pesanan |
| Our Team | tidak | ya | ya |
| Panel Admin (`/admin`) | tidak | 403 | ya |
| Nilai Islam (PAIBP/Arab), Diskusi Ekonomi | tidak | 403 | ya (menu "Materi") |
| Footer dengan WhatsApp | ya | ya | ya |

Setelah login, admin diarahkan ke `/admin`, pelanggan ke `/dashboard` seperti sebelumnya. Peran disimpan di kolom `users.role` (`customer` atau `admin`). Kolom ini sengaja tidak masuk `$fillable`, jadi form registrasi tidak bisa dipakai untuk menjadi admin (ada test-nya).

## 3. Langkah implementasi bertahap

Tiap tahap sudah saya jalankan terpisah di salinan bersih repo, dan hasilnya hijau.

Tahap 0, sebelum mulai:
```bash
git checkout -b fitur-foodtech-3-peran
php artisan test          # baseline: 127 lulus
```

**Tahap 1: data dan peran** (belum ada perubahan tampilan)
Salin: `database/migrations/2026_11_21_100000_...php`, `app/Models/User.php`, `app/Models/Product.php`, `app/Http/Middleware/EnsureUserIsAdmin.php`, `bootstrap/app.php`, `database/factories/UserFactory.php`, `config/lumeria.php`, `database/seeders/DatabaseSeeder.php`, `database/seeders/ProductSeeder.php`, `.env.example`, `lang/id/foodtech.php`, `lang/en/foodtech.php`, `app/Support/StoreContact.php`
```bash
php artisan migrate
php artisan test          # tetap 127 lulus
```

**Tahap 2: akses 3 peran, footer, detail produk**
Salin: `public/css/lumeria-foodtech.css`, `components/icon.blade.php`, `layouts/lumeria.blade.php`, `partials/footer.blade.php`, `partials/navbar.blade.php`, `routes/web.php`, `Admin/DashboardController.php`, `OrderController.php`, `ProductController.php`, `AuthenticatedSessionController.php`, `admin/dashboard.blade.php`, `pages/order-history.blade.php`, `pages/product-show.blade.php`, `pages/order.blade.php`, dan test: `IslamicLangTest`, `OrderFlowTest`, `PublicPagesTest`, `ResponsiveUiTest`, `StaticPagesTest`, `TeamPageTest`, `RoleAccessTest`
```bash
php artisan test          # 143 lulus
```

**Tahap 3: kartu badge, carousel, kartu catering, beranda**
Salin: `partials/product-card.blade.php`, `partials/product-carousel.blade.php`, `partials/catering-card.blade.php`, `pages/home.blade.php`, `PageController.php`, `tests/Feature/FoodtechUiTest.php`
```bash
php artisan test          # 159 lulus
```

**Tahap 4: isi data dan cek manual**
```bash
# .env: nomor WhatsApp toko (footer, kartu catering, detail produk). Kosong atau tidak valid = tombolnya otomatis hilang.
LUMERIA_WHATSAPP=0812-3456-7890
LUMERIA_INSTAGRAM=@akun_toko        # opsional

# Jadikan satu akun admin (aman dijalankan pada database yang sudah berisi data)
php artisan tinker
>>> App\Models\User::where('email', 'email-admin@contoh.com')->update(['role' => 'admin']);

# Beri badge ke produk (promo | discount | best_seller | new, atau null untuk mencabut)
>>> App\Models\Product::where('slug', 'bola-kopi')->update(['badge' => 'promo']);
```
`php artisan db:seed` aman dijalankan berulang: `ProductSeeder` hanya membuat produk yang belum ada (suntingan admin tidak ditimpa) dan `AdminSeeder` mencari akun lewat email (tidak menggandakan). Opsi `LUMERIA_ADMIN_EMAIL` dan `LUMERIA_ADMIN_PASSWORD` di `.env` membuat satu admin tambahan di luar keempat admin tim.

## 4. Dampak ke test yang sudah ada

| Test | Perubahan | Alasan |
|---|---|---|
| `PublicPagesTest` (beranda ID dan EN) | `assertSee('Tim Kami')` / `'Our Team'` menjadi `assertDontSee` | Menu Our Team tidak tampil untuk tamu |
| `StaticPagesTest` | Islamic dan Economic memakai admin; Team memakai pelanggan; "every navbar page" dipecah menjadi tamu dan admin | Rute dibatasi per peran |
| `TeamPageTest` | `setUp` login sebagai pelanggan | `/our-team` kini di grup `auth` |
| `IslamicLangTest` | 1 test memakai admin | `/islamic-values` kini khusus admin |
| `OrderFlowTest` | Keranjang dan checkout memakai `actingAs`; "guest can checkout" menjadi "customer can checkout"; ada test baru "tamu tidak bisa" | Keranjang dan checkout kini untuk pelanggan |
| `ResponsiveUiTest` | 2 test (tambah ke keranjang tanpa referer, error checkout) memakai `actingAs` | Sama |
| `ContactLinksTest`, `ProductArtTest`, `ThemeTest`, `OrderPageTest`, `PaylaterCalculatorTest`, test Auth dan Profile | Tidak berubah | Tidak terdampak |

Test baru: `RoleAccessTest` (14 test: akses per peran, redirect login, registrasi tidak bisa jadi admin, riwayat pesanan hanya milik sendiri) dan `FoodtechUiTest` (16 test: badge, detail produk, carousel, kartu catering, footer WhatsApp, kesamaan key bahasa).

## 5. Jebakan yang sudah ada di test repo (supaya tidak kena lagi)

- `ResponsiveUiTest` melarang emoji di `views`, `lang`, dan `seeders`. Ikon WhatsApp dan Instagram memakai SVG inline (`<x-icon>`), jangan diganti emoji.
- `ThemeTest` melarang kata `http` di `public/css/lumeria.css`. Karena itu gaya baru ada di berkas terpisah `lumeria-foodtech.css`.
- `ProductArtTest` menghitung persis jumlah `class="product-art"` di `/products` (6) dan `/` (3). Carousel memakai `<x-product-art :bare="true">` supaya hitungan tidak bertambah.
- Kartu produk sengaja hanya punya satu tombol (ke detail). "Tambah ke keranjang" pindah ke halaman detail, dan `CartController` tetap kembali ke `#product-{id}` di halaman itu.

## 6. Yang sudah dan belum diverifikasi

Sudah: saya merekonstruksi proyek dari `composer.lock` di sandbox (Laravel 12, PHP 8.3, SQLite) dan menjalankan `php artisan test`: 127 lulus sebelum perubahan, 159 lulus sesudahnya, dan tiap tahap di atas lulus sendiri-sendiri. Migrasi, rollback, dan seeder juga dicoba pada SQLite sungguhan. Ikon WhatsApp/Instagram sudah saya render dan lihat hasilnya.

Belum:
- **Tampilan di browser.** Carousel (geser, tombol, titik), kartu catering, footer, dan dropdown "Materi" belum saya lihat di browser. Tolong dibuka di HP dan desktop sebelum ditunjukkan ke CEO.
- **`resources/views/pages/islamic.blade.php`** terbaca sebagai berkas biner di dump repo, jadi isinya tidak ikut ke paket ini dan tidak saya ubah. Di sandbox saya memakai stub sementara agar test-nya bisa jalan, jadi test isi halaman Islam (`StaticPagesTest`, `IslamicLangTest`) belum teruji terhadap view aslinya. Yang saya ubah hanya siapa yang boleh membukanya, jadi seharusnya tetap lulus, tetapi jalankan `php artisan test` di repo Anda untuk memastikan.
- Paket ini dibuat dari snapshot dump repo. Bila branch Anda sudah berubah sejak itu, bandingkan dengan `perubahan-berkas-lama.diff` sebelum menimpa berkas yang sudah ada.

## 7. Di luar cakupan (tindak lanjut)

- **Harga diskon sungguhan.** Badge Promo dan Diskon saat ini hanya label. Menghitung harga coret/diskon menyentuh `Cart`, `OrderItem`, dan `PaylaterCalculator`, jadi sebaiknya jadi tugas terpisah.
- **Admin mengubah produk dan badge dari layar.** Panel admin sekarang hanya membaca (ringkasan, pesanan terbaru, daftar produk, tautan materi).
- **Mengubah status pesanan.** Belum ada; `order.status` baru bernilai `pending`.
