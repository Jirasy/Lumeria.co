# Panduan Tim Lumeria.co

## 1. Syarat
- Akun GitHub dan sudah menerima undangan **Collaborator** dari pemilik repo (cek email atau notifikasi GitHub).
- Terpasang: Git, PHP (versi sama dengan pemilik repo, cek `php -v`), Composer, Node.js LTS.
- Belum punya semuanya? Lewati ke bagian 5 (edit teks lewat GitHub, tanpa instalasi).

## 2. Setup (sekali saja)

```bash
git clone https://github.com/Jirasy/Lumeria.co.git
cd Lumeria.co

git config user.name "Nama Kamu"
git config user.email "email-yang-dipakai-di-github@contoh.com"

composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite      # PowerShell: New-Item database/database.sqlite
php artisan migrate
php artisan db:seed --class=ProductSeeder
npm run build
php artisan test
php artisan serve
```

Pastikan `.env` berisi `DB_CONNECTION=sqlite`. Buka http://127.0.0.1:8000.
Kalau `php artisan test` tidak hijau, jangan mulai bekerja dan kirim hasilnya ke pemilik repo.

Database SQLite ada di laptop masing-masing dan tidak ikut Git. Akun dan pesanan yang kamu buat saat mencoba hanya ada di laptopmu.

## 3. Alur kerja setiap kali

```bash
git checkout main
git pull origin main
git checkout -b feat/nama-singkat-fitur

# ... ubah file ...

php artisan test
git status
git add <file-yang-kamu-ubah>
git commit -m "feat: deskripsi singkat"
git push -u origin feat/nama-singkat-fitur
```

Di GitHub klik **Compare & pull request**, tulis apa yang diubah, lalu minta pemilik repo me-review dan me-merge. Jangan push langsung ke `main`.

## 4. Aturan agar tidak bentrok

- Satu orang, satu set file (usulan; sesuaikan):

| Anggota | File yang diubah |
|---|---|
| Sanaya | `lang/id/home.php`, `lang/en/home.php` (visi, misi, filosofi) |
| Aliful | `database/seeders/ProductSeeder.php` (deskripsi produk) |
| Alfatih | `lang/id/islamic.php`, `lang/en/islamic.php` (bagian `apply`) |
| Pemilik repo | kode, `routes/`, `resources/views/`, `config/` |

- Jangan ubah `routes/web.php`, `resources/views/layouts/`, `resources/views/partials/`, atau `lang/*/nav.php` tanpa memberi tahu pemilik repo.
- Jangan commit `.env` atau file `*.sqlite`. Pakai `git add <file>`, bukan `git add .`.
- Selalu mulai dengan `git pull origin main`.
- Kalau muncul konflik (tanda `<<<<<<<`), berhenti dan minta bantuan. Jangan hapus tandanya sembarangan.
- Push ditolak 403: kamu belum menjadi Collaborator. Push ditolak "non-fast-forward": jalankan `git pull origin main` dulu.

## 5. Mengedit teks tanpa instalasi

1. Buka file di GitHub (misalnya `lang/id/home.php`) dan klik ikon pensil.
2. Ubah teks di antara tanda kutip. Jangan hapus tanda kutip, koma, atau titik koma.
3. Klik **Commit changes**, pilih **Create a new branch for this commit and start a pull request**, lalu buat PR.

Satu tanda kutip yang hilang membuat situs error. Pemilik repo menjalankan ini sebelum merge:

## 6. Error aneh setelah `git pull`

Jalankan ini setelah pull yang mengubah `composer.lock`, `package.json`, atau folder `database/migrations`:

```bash
composer install
php artisan migrate
npm install
npm run build
```

Kalau muncul error `Class "..." not found` saat menjalankan `php artisan`, bersihkan cache paket lalu coba lagi:

```bash
rm -f bootstrap/cache/packages.php bootstrap/cache/services.php
composer install
php artisan package:discover
```

Masih gagal? Kirim error lengkapnya ke pemilik repo. Jangan menghapus folder `vendor/` dan jangan menjalankan `composer update`.

```bash
git fetch origin
git checkout nama-branch-temanmu
php artisan test
```