<?php

/*
|--------------------------------------------------------------------------
| Komponen etalase foodtech (Bahasa Indonesia)
|--------------------------------------------------------------------------
|
| Dipakai oleh: partials/product-card, product-carousel, catering-card, footer,
| pages/product-show, pages/order-history, admin/dashboard.
| Susunan key harus sama persis dengan lang/en/foodtech.php.
| Teks catering di bawah adalah contoh; sesuaikan dengan layanan yang benar-benar ada.
|
*/

return [

    'nav' => [
        'my_orders' => 'Pesanan Saya',
        'admin'     => 'Panel Admin',
        'materials' => 'Materi',
    ],

    // Label badge pojok kartu produk. Kunci harus sama dengan Product::BADGES.
    'badges' => [
        'promo'       => 'Promo',
        'discount'    => 'Diskon',
        'best_seller' => 'Terlaris',
        'new'         => 'Baru',
    ],

    'card' => [
        'detail' => 'Lihat Detail',
    ],

    'detail' => [
        'back'          => 'Semua produk',
        'login_to_order' => 'Masuk untuk memesan',
        'register'      => 'Daftar akun baru',
        'guest_note'    => 'Keranjang dan checkout tersedia untuk pelanggan yang sudah masuk.',
        'order_wa'      => 'Tanya atau pesan lewat WhatsApp',
        'wa_message'    => 'Halo Lumeria.co, saya tertarik dengan :product.',
        'paylater'      => 'Simulasikan Paylater untuk produk ini',
        'related'       => 'Camilan lain dari Lumeria.co',
    ],

    'catering' => [
        'label'     => 'Lumeria Catering',
        'title'     => 'Camilan lumer untuk setiap acara',
        'text'      => 'Pesan bola susu dan dessert lumer dalam jumlah banyak untuk arisan, rapat, ulang tahun, atau acara kampus. Sebutkan jumlah dan tanggalnya, kami bantu hitung pesanannya.',
        'cta_order' => 'Pesan Catering',
        'cta_more'  => 'Pelajari Lebih Lanjut',
        'wa_message' => 'Halo Lumeria.co, saya ingin bertanya soal pesanan catering untuk acara.',
    ],

    'carousel' => [
        'title'  => 'Jelajahi Menu',
        'sub'    => 'Geser untuk melihat semua camilan lumer Lumeria.co.',
        'all'    => 'Lihat semua produk',
        'region' => 'Daftar produk Lumeria.co',
        'prev'   => 'Geser ke kiri',
        'next'   => 'Geser ke kanan',
        'dot'    => 'Ke halaman :n',
    ],

    'footer' => [
        'explore'    => 'Jelajahi',
        'account'    => 'Akun',
        'social'     => 'Tautan sosial',
        'whatsapp'   => 'WhatsApp',
        'chat_wa'    => 'Chat lewat WhatsApp',
        'instagram'  => 'Instagram',
        'wa_message' => 'Halo Lumeria.co, saya ingin bertanya.',
        'customer_service' => 'Layanan Pelanggan',
        'how_to_order'     => 'Cara Memesan',
        'track_order'      => 'Lacak Pesanan',
        'contact_us'       => 'Hubungi Kami',
        'payment'          => 'Pembayaran',
        'pay_cash'         => 'Tunai',
        'pay_paylater'     => 'Paylater',
        'pay_note'         => 'Cicilan :min sampai :max bulan',
        'follow'           => 'Ikuti Kami',
    ],

    'guest_order' => [
        'title' => 'Masuk dulu untuk memesan',
        'text'  => 'Masuk atau daftar untuk menambahkan produk ke keranjang dan melakukan checkout. Simulasi Paylater di halaman ini bisa dicoba tanpa akun.',
    ],

    'history' => [
        'title'    => 'Pesanan Saya',
        'subtitle' => 'Riwayat dan status pesananmu.',
        'empty'    => 'Belum ada pesanan.',
        'order'    => 'Pesanan',
        'date'     => 'Tanggal',
        'items'    => 'Item',
        'payment'  => 'Pembayaran',
        'total'    => 'Total',
        'status'   => 'Status',
        'view'     => 'Lihat',
    ],

    'admin' => [
        'title'        => 'Panel Admin',
        'subtitle'     => 'Ringkasan toko, pesanan terbaru, dan materi khusus admin.',
        'stat_products'  => 'Produk aktif',
        'stat_orders'    => 'Total pesanan',
        'stat_pending'   => 'Pesanan menunggu',
        'stat_customers' => 'Pelanggan',
        'latest_orders'  => 'Pesanan terbaru',
        'no_orders'      => 'Belum ada pesanan masuk.',
        'products'       => 'Daftar produk',
        'category'       => 'Kategori',
        'price'          => 'Harga',
        'badge'          => 'Badge',
        'active'         => 'Aktif',
        'yes'            => 'Ya',
        'no'             => 'Tidak',
        'customer'       => 'Pemesan',
        'materials'      => 'Materi khusus admin',
        'materials_text' => 'Bagian ini tidak tampil untuk tamu maupun pelanggan.',
    ],

];
