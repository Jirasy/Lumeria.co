<?php

/*
|--------------------------------------------------------------------------
| Halaman Beranda (Bahasa Indonesia)
|--------------------------------------------------------------------------
|
| Dipakai oleh resources/views/pages/home.blade.php lewat __('home.key').
| Susunan key harus sama persis dengan lang/en/home.php. Jika menambah
| atau mengubah key, ubah kedua file sekaligus.
|
*/

return [

    // Judul halaman, hero, dan tagline
    'title' => 'Beranda',
    'hero_identity' => 'Camilan manis dan lembut yang cepat lumer.',
    'tagline' => 'Mood booster dalam satu gigitan',
    'cta_products' => 'Lihat Produk',
    'cta_order' => 'Pesan Sekarang',

    // Hero
    'eyebrow' => 'Camilan lumer buatan tangan',
    'hero_title' => 'Kebahagiaan kecil yang lumer di setiap gigitan',
    'hero_note' => 'Bola susu, bolu lumer, kue balok, dan es krim creamy.',
    'hero_caption_title' => 'Bola susu signature',
    'hero_caption' => 'Tiga rasa andalan: susu, teh, dan kopi.',
    'hero_point_1' => 'Bahan pilihan',
    'hero_point_2' => 'Porsi pas',
    'hero_point_3' => 'Pesan online atau WhatsApp',
    'marquee' => [
        'Bola susu',
        'Bola teh',
        'Bola kopi',
        'Bolu lumer',
        'Kue balok',
        'Es krim creamy',
    ],

    // Menu pilihan
    'favorites_eyebrow' => 'Menu pilihan',
    'favorites_title' => 'Camilan yang bikin hari lebih ringan',
    'favorites_sub' => 'Dessert lumer di luar bola susu, dari bolu hangat sampai es krim creamy.',
    'view_all' => 'Lihat semua',

    // Kategori
    'categories_eyebrow' => 'Jelajahi',
    'categories_title' => 'Ada banyak cara untuk lumer',
    'categories_sub' => 'Mulai dari bola susu signature sampai pesanan catering untuk acara.',
    'cat_bola_title' => 'Bola Susu',
    'cat_bola_text' => 'Tiga rasa signature yang lumer di mulut: susu, teh, dan kopi.',
    'cat_lumer_title' => 'Dessert Lumer',
    'cat_lumer_text' => 'Bolu lumer dan kue balok hangat untuk gigitan yang lebih membumi.',
    'cat_es_title' => 'Es Krim',
    'cat_es_text' => 'Es krim creamy yang lembut dan segar untuk menutup hari.',
    'cat_catering_title' => 'Catering & Acara',
    'cat_catering_text' => 'Pesan dalam jumlah banyak untuk arisan, rapat, atau hadiah kecil.',
    'cat_cta' => 'Buka',

    // Produk andalan
    'signature_title' => 'Bola Susu Signature',
    'signature_sub' => 'Tiga rasa andalan Lumeria.co yang lumer di setiap gigitan.',

    // Cerita merek
    'story_eyebrow' => 'Tentang kami',
    'story_title' => 'Dari dapur kecil, untuk jeda yang menyenangkan',
    'story_cta' => 'Kenali camilan kami',
    'story_quote_label' => 'Filosofi Lumeria.co',

    // Tentang kami
    'about_title' => 'Tentang Lumeria.co',
    'about_text' => 'Lumeria.co adalah usaha camilan bola susu dan dessert lumer yang manis dan lembut. Kami ingin menghadirkan jeda kecil yang menyenangkan di sela hari yang sibuk.',

    // Visi
    'vision_title' => 'Visi',
    'vision_text' => 'Menjadi pilihan utama camilan dan dessert lumer yang menghadirkan kebahagiaan sederhana bagi setiap orang.',

    // Misi (daftar; ditampilkan sebagai poin-poin)
    'mission_title' => 'Misi',
    'mission_items' => [
        'Menyajikan camilan dengan takaran pas, rasa yang konsisten, dan bahan yang baik.',
        'Menjaga kebersihan di setiap tahap pembuatan, dari dapur kami hingga ke tangan pembeli.',
        'Melayani dengan jujur dan amanah, menjelaskan produk apa adanya.',
        'Menyediakan pilihan pembayaran yang transparan dan mudah dipahami, dengan rincian biaya yang jelas sebelum pesanan dibuat.',
    ],

    // Filosofi
    'philosophy_title' => 'Kecil, tapi berarti',
    'philosophy_text' => 'Sepotong camilan kecil bisa menjadi alasan seseorang tersenyum hari ini. Kami percaya hal-hal kecil yang dikerjakan dengan jujur dan sepenuh hati akan terasa besar artinya.',

    // Pilar
    'benefits_eyebrow' => 'Kenapa Lumeria.co',
    'benefits_title' => 'Hal kecil yang kami jaga',
    'benefits' => [
        [
            'title' => 'Bahan yang dipilih',
            'text' => 'Kami memilih bahan dengan rasa yang konsisten dan takaran yang pas, supaya manisnya tidak berlebihan.',
        ],
        [
            'title' => 'Bersih di setiap tahap',
            'text' => 'Dari dapur sampai ke tanganmu, kebersihan dijaga di setiap langkah pembuatan.',
        ],
        [
            'title' => 'Jujur soal produk',
            'text' => 'Deskripsi dan harga ditulis apa adanya, tanpa klaim yang tidak bisa kami penuhi.',
        ],
        [
            'title' => 'Pembayaran transparan',
            'text' => 'Rincian biaya tampil sebelum pesanan dibuat, termasuk simulasi Paylater.',
        ],
    ],

    // Cara pesan
    'steps_eyebrow' => 'Cara pesan',
    'steps_title' => 'Tiga langkah, satu gigitan',
    'steps' => [
        [
            'title' => 'Pilih camilanmu',
            'text' => 'Buka menu, bandingkan rasa, lalu tambahkan yang ingin kamu coba ke keranjang.',
        ],
        [
            'title' => 'Lengkapi pesanan',
            'text' => 'Masuk atau daftar, isi nama dan nomor telepon, lalu pilih metode pembayaran yang tersedia.',
        ],
        [
            'title' => 'Nikmati saat tiba',
            'text' => 'Kami siapkan pesananmu, dan statusnya bisa kamu pantau di halaman Pesanan Saya.',
        ],
    ],

    // FAQ
    'faq_eyebrow' => 'Tanya jawab',
    'faq_title' => 'Sering ditanyakan',
    'faq' => [
        [
            'q' => 'Apakah saya bisa memesan tanpa membuat akun?',
            'a' => 'Bisa. Menu dan harga terbuka untuk semua orang, dan pesanan bisa ditanyakan lewat WhatsApp. Keranjang serta checkout otomatis tersedia setelah kamu masuk atau mendaftar.',
        ],
        [
            'q' => 'Bagaimana cara menyimpan camilan Lumeria.co?',
            'a' => 'Simpan bola susu di wadah tertutup pada suhu ruang, jauh dari sinar matahari langsung, lalu nikmati dalam beberapa hari setelah diterima. Dessert lumer sebaiknya dihangatkan sebentar sebelum disantap.',
        ],
        [
            'q' => 'Bisa pesan untuk acara atau jumlah banyak?',
            'a' => 'Bisa. Kami menerima pesanan catering untuk arisan, rapat, ulang tahun, dan acara kampus. Sebutkan jumlah dan tanggalnya lewat WhatsApp atau halaman pemesanan agar pesanan bisa dihitung bersama.',
        ],
        [
            'q' => 'Metode pembayaran apa saja yang tersedia?',
            'a' => 'Rincian biaya ditampilkan sebelum pesanan dibuat. Halaman pemesanan juga menyediakan simulasi Paylater supaya kamu bisa memperkirakan cicilan sebelum memutuskan.',
        ],
        [
            'q' => 'Bagaimana cara menghubungi Lumeria.co?',
            'a' => 'Gunakan tombol WhatsApp atau Instagram di bagian bawah halaman. Tim kami akan membantu memilih camilan yang cocok untukmu.',
        ],
    ],

    // Galeri
    'gallery_eyebrow' => 'Galeri',
    'gallery_title' => 'Sedikit potret dari dapur kami',
    'gallery_cta' => 'Ikuti Instagram',

    // Pita ajakan
    'cta_title' => 'Siap mencoba camilan lumer?',
    'cta_text' => 'Lihat menu lengkap, pilih rasa favoritmu, dan pesan langsung dari website.',
];
