<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Catatan: slug, name, price, category, dan is_signature jangan diubah
        // sembarangan. Tes (PublicPagesTest, OrderFlowTest, OrderPageTest) dan
        // emoji di partials/product-card.blade.php bergantung pada nilai-nilai itu.
        $products = [
            // ===== Bola Susu (Signature) =====
            [
                'category'       => 'bola_susu',
                'name'           => 'Original Milk',
                'slug'           => 'original-milk',
                'price'          => 12000,
                'is_signature'   => true,
                'description_id' => 'Bola susu lembut dengan rasa susu murni yang langsung lumer di mulut. Manisnya pas dan tidak berlebihan, cocok untuk pencinta rasa klasik yang sederhana.',
                'description_en' => 'A soft milk ball with pure milky flavor that melts in your mouth. Its gentle, balanced sweetness makes it a perfect pick for fans of a simple classic.',
            ],
            [
                'category'       => 'bola_susu',
                'name'           => 'Bola Teh',
                'slug'           => 'bola-teh',
                'price'          => 12000,
                'is_signature'   => true,
                'description_id' => 'Bola susu beraroma teh yang harum dengan manis yang lembut. Perpaduan susu dan teh yang menenangkan, pas dinikmati sebagai teman santai di sore hari.',
                'description_en' => 'A milk ball infused with fragrant tea and gentle sweetness. A soothing blend of milk and tea, perfect as an easygoing afternoon companion.',
            ],
            [
                'category'       => 'bola_susu',
                'name'           => 'Bola Kopi',
                'slug'           => 'bola-kopi',
                'price'          => 12000,
                'is_signature'   => true,
                'description_id' => 'Bola susu rasa kopi untuk mood booster dalam satu gigitan. Aroma kopinya terasa, dipadukan dengan susu yang lembut agar tidak terlalu pahit.',
                'description_en' => 'A coffee-flavored milk ball: a mood booster in one bite. A rich coffee aroma balanced with smooth milk, so it is never too bitter.',
            ],

            // ===== Varian Lumer Lainnya =====
            [
                'category'       => 'lumer_lainnya',
                'name'           => 'Bolu Lumer Cokelat/Keju',
                'slug'           => 'bolu-lumer-cokelat-keju',
                'price'          => 15000,
                'is_signature'   => false,
                'description_id' => 'Bolu empuk dengan isian cokelat atau keju yang lumer saat digigit. Pilih cokelat yang manis legit atau keju yang gurih, keduanya paling nikmat disantap hangat.',
                'description_en' => 'A fluffy sponge cake with a melting chocolate or cheese center. Choose rich, sweet chocolate or savory cheese; both are best enjoyed warm.',
            ],
            [
                'category'       => 'lumer_lainnya',
                'name'           => 'Kue Balok Lumer',
                'slug'           => 'kue-balok-lumer',
                'price'          => 10000,
                'is_signature'   => false,
                'description_id' => 'Kue balok hangat dengan bagian dalam yang manis dan lumer. Renyah di luar dan lembut di dalam, jajanan tradisional dengan sentuhan lumer khas Lumeria.co.',
                'description_en' => 'A warm kue balok with a sweet, melting center. Crisp on the outside and soft on the inside, a traditional snack with a signature Lumeria.co melty twist.',
            ],
            [
                'category'       => 'lumer_lainnya',
                'name'           => 'Es Krim Lumeria Creamy',
                'slug'           => 'es-krim-lumeria-creamy',
                'price'          => 8000,
                'is_signature'   => false,
                'description_id' => 'Es krim creamy yang lembut dan segar, pas untuk menutup hari. Teksturnya halus dan dingin di mulut, pasangan manis setelah camilan hangat.',
                'description_en' => 'Soft, creamy ice cream: a fresh way to end the day. Smooth and cool on the tongue, a sweet partner to any warm treat.',
            ],
        ];

        // updateOrCreate memakai slug sebagai kunci, jadi seeder aman dijalankan
        // berulang: data yang sudah ada diperbarui, bukan diduplikasi.
        foreach ($products as $data) {
            Product::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
