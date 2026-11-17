<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // ===== Bola Susu (Signature) =====
            [
                'category'       => 'bola_susu',
                'name'           => 'Original Milk',
                'slug'           => 'original-milk',
                'price'          => 12000,
                'is_signature'   => true,
                'description_id' => 'Bola susu lembut dengan rasa susu murni yang langsung lumer di mulut.',
                'description_en' => 'A soft milk ball with pure milky flavor that melts in your mouth.',
            ],
            [
                'category'       => 'bola_susu',
                'name'           => 'Bola Teh',
                'slug'           => 'bola-teh',
                'price'          => 12000,
                'is_signature'   => true,
                'description_id' => 'Bola susu beraroma teh yang harum dengan manis yang lembut.',
                'description_en' => 'A milk ball infused with fragrant tea and gentle sweetness.',
            ],
            [
                'category'       => 'bola_susu',
                'name'           => 'Bola Kopi',
                'slug'           => 'bola-kopi',
                'price'          => 12000,
                'is_signature'   => true,
                'description_id' => 'Bola susu rasa kopi untuk mood booster dalam satu gigitan.',
                'description_en' => 'A coffee-flavored milk ball: a mood booster in one bite.',
            ],

            // ===== Varian Lumer Lainnya =====
            [
                'category'       => 'lumer_lainnya',
                'name'           => 'Bolu Lumer Cokelat/Keju',
                'slug'           => 'bolu-lumer-cokelat-keju',
                'price'          => 15000,
                'is_signature'   => false,
                'description_id' => 'Bolu empuk dengan isian cokelat atau keju yang lumer saat digigit.',
                'description_en' => 'A fluffy sponge cake with a melting chocolate or cheese center.',
            ],
            [
                'category'       => 'lumer_lainnya',
                'name'           => 'Kue Balok Lumer',
                'slug'           => 'kue-balok-lumer',
                'price'          => 10000,
                'is_signature'   => false,
                'description_id' => 'Kue balok hangat dengan bagian dalam yang manis dan lumer.',
                'description_en' => 'A warm kue balok with a sweet, melting center.',
            ],
            [
                'category'       => 'lumer_lainnya',
                'name'           => 'Es Krim Lumeria Creamy',
                'slug'           => 'es-krim-lumeria-creamy',
                'price'          => 8000,
                'is_signature'   => false,
                'description_id' => 'Es krim creamy yang lembut dan segar, pas untuk menutup hari.',
                'description_en' => 'Soft, creamy ice cream: a fresh way to end the day.',
            ],
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}