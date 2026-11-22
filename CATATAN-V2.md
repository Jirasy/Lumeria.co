# Lumeria.co foodtech: pembaruan v2

Berlaku di atas paket pertama. Menyesuaikan perilaku keranjang tamu dengan `CartAuthTest.php` di repo Anda.

## Salin (berkas penuh, semuanya berkas yang memang saya kirim di paket pertama)
- routes/web.php
- lang/id/foodtech.php, lang/en/foodtech.php
- resources/views/partials/product-card.blade.php
- tests/Feature/OrderFlowTest.php, PublicPagesTest.php, ResponsiveUiTest.php, FoodtechUiTest.php, RoleAccessTest.php

## Edit manual (berkas asli repo Anda, jadi tidak saya timpa)
- app/Services/Cart.php: lihat EDIT-MANUAL-Cart.diff (satu guard di `raw()`)
- tests/Feature/OrderPageTest.php: lihat EDIT-MANUAL-OrderPageTest.diff. Kalau test Anda sudah memakai actingAs, lewati.

`v1-ke-v2-semua-perubahan.diff` berisi semuanya sekaligus, untuk dibandingkan dengan `git diff` Anda.

Hasil: 4 asersi CartAuthTest yang gagal sudah terpenuhi (diuji dengan stand-in dari asersi yang terlihat di output Anda, bukan berkas aslinya).
