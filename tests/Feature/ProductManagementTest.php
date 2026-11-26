<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Kelola produk (CRUD) hanya untuk admin.
 */
class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    private const PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';

    protected function setUp(): void
    {
        parent::setUp();

        // Jangan menulis ke public/images/products sungguhan.
        Storage::fake('products');
    }

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    /** File sungguhan (bukan fake) agar tipe diperiksa dari ISI berkas, seperti di aplikasi. */
    private function realFile(string $name, string $content): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'prd');
        file_put_contents($path, $content);

        return new UploadedFile($path, $name, null, null, true);
    }

    private function png(string $name = 'foto.png', int $paddingKb = 0): UploadedFile
    {
        return $this->realFile($name, base64_decode(self::PNG) . str_repeat("\0", $paddingKb * 1024));
    }

    private function data(array $overrides = []): array
    {
        return array_merge([
            'name'           => 'Bola Matcha',
            'category'       => 'bola_susu',
            'price'          => '13500',
            'description_id' => 'Bola susu rasa matcha yang lumer.',
            'description_en' => 'A melting matcha milk ball.',
            'badge'          => 'new',
            'is_signature'   => '1',
            'is_active'      => '1',
        ], $overrides);
    }

    private function makeProduct(array $overrides = []): Product
    {
        return Product::create(array_merge([
            'category' => 'bola_susu', 'name' => 'Produk Uji', 'slug' => 'produk-uji', 'price' => 10000,
            'description_id' => 'Deskripsi', 'description_en' => 'Description', 'is_active' => true,
        ], $overrides));
    }

    // ---------- Hak akses ----------

    public function test_guest_is_sent_to_login_for_every_product_admin_route(): void
    {
        $p = $this->makeProduct();

        $this->get(route('admin.products.index'))->assertRedirect(route('login'));
        $this->get(route('admin.products.create'))->assertRedirect(route('login'));
        $this->post(route('admin.products.store'), $this->data())->assertRedirect(route('login'));
        $this->get(route('admin.products.edit', $p))->assertRedirect(route('login'));
        $this->put(route('admin.products.update', $p), $this->data())->assertRedirect(route('login'));
        $this->delete(route('admin.products.destroy', $p))->assertRedirect(route('login'));
    }

    public function test_customer_is_forbidden_from_every_product_admin_route(): void
    {
        $p = $this->makeProduct();
        $this->actingAs(User::factory()->create());

        $this->get(route('admin.products.index'))->assertForbidden();
        $this->get(route('admin.products.create'))->assertForbidden();
        $this->post(route('admin.products.store'), $this->data())->assertForbidden();
        $this->get(route('admin.products.edit', $p))->assertForbidden();
        $this->put(route('admin.products.update', $p), $this->data(['name' => 'Diretas']))->assertForbidden();
        $this->delete(route('admin.products.destroy', $p))->assertForbidden();

        $this->assertDatabaseMissing('products', ['name' => 'Bola Matcha']);
        $this->assertSame('Produk Uji', $p->fresh()->name);
        $this->assertModelExists($p);
    }

    public function test_only_admins_see_product_management_links(): void
    {
        $this->actingAs(User::factory()->create())->get('/')->assertDontSee(route('admin.products.index'), false);
        $this->actingAs($this->admin())->get('/')->assertSee(route('admin.products.index'), false);
        $this->get(route('admin.dashboard'))
            ->assertSee(route('admin.products.index'), false)
            ->assertSee(route('admin.products.create'), false);
    }

    // ---------- Daftar ----------

    public function test_admin_sees_all_products_including_inactive_ones(): void
    {
        $this->makeProduct(['name' => 'Tampil', 'slug' => 'tampil']);
        $this->makeProduct(['name' => 'Tersembunyi', 'slug' => 'tersembunyi', 'is_active' => false]);

        $this->actingAs($this->admin())->withCookie('locale', 'id')->get(route('admin.products.index'))
            ->assertOk()
            ->assertSee('Kelola Produk')
            ->assertSee('Tampil')
            ->assertSee('Tersembunyi')
            ->assertSee('Nonaktif')
            ->assertSee('table-responsive', false);
    }

    public function test_list_can_be_searched_and_filtered(): void
    {
        $this->makeProduct(['name' => 'Bola Matcha', 'slug' => 'bola-matcha', 'category' => 'bola_susu']);
        $this->makeProduct(['name' => 'Kue Cubit', 'slug' => 'kue-cubit', 'category' => 'lumer_lainnya', 'is_active' => false]);
        $this->actingAs($this->admin());

        $this->get(route('admin.products.index', ['q' => 'matcha']))->assertSee('Bola Matcha')->assertDontSee('Kue Cubit');
        $this->get(route('admin.products.index', ['category' => 'lumer_lainnya']))->assertSee('Kue Cubit')->assertDontSee('Bola Matcha');
        $this->get(route('admin.products.index', ['status' => 'inactive']))->assertSee('Kue Cubit')->assertDontSee('Bola Matcha');
        $this->get(route('admin.products.index', ['status' => 'active']))->assertSee('Bola Matcha')->assertDontSee('Kue Cubit');
        $this->get(route('admin.products.index', ['q' => '%']))->assertDontSee('Bola Matcha')->assertSee(__('products_admin.empty'));
    }

    public function test_list_is_paginated(): void
    {
        foreach (range(1, 13) as $i) {
            $this->makeProduct(['name' => "Produk {$i}", 'slug' => "produk-{$i}"]);
        }

        $this->actingAs($this->admin())->get(route('admin.products.index'))->assertOk()->assertSee('page=2', false);
        $this->get(route('admin.products.index', ['page' => 2]))->assertOk();
    }

    // ---------- Tambah ----------

    public function test_admin_can_create_a_product_with_a_generated_slug(): void
    {
        $this->actingAs($this->admin())->post(route('admin.products.store'), $this->data())
            ->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('status');

        $product = Product::where('name', 'Bola Matcha')->firstOrFail();
        $this->assertSame('bola-matcha', $product->slug);
        $this->assertSame(13500, $product->price);
        $this->assertSame('bola_susu', $product->category);
        $this->assertSame('new', $product->badge);
        $this->assertTrue($product->is_signature);
        $this->assertTrue($product->is_active);
        $this->assertNull($product->image);

        $this->get(route('products.show', $product))->assertOk()->assertSee('Bola Matcha');
    }

    public function test_unchecked_boxes_mean_false_and_empty_badge_means_null(): void
    {
        $this->actingAs($this->admin())->post(route('admin.products.store'), $this->data(['badge' => '', 'is_signature' => null, 'is_active' => null]));

        $product = Product::where('name', 'Bola Matcha')->firstOrFail();
        $this->assertNull($product->badge);
        $this->assertFalse($product->is_signature);
        $this->assertFalse($product->is_active);
    }

    public function test_duplicate_names_get_unique_slugs_and_odd_names_get_a_fallback(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.products.store'), $this->data());
        $this->post(route('admin.products.store'), $this->data());
        $this->post(route('admin.products.store'), $this->data());
        $this->post(route('admin.products.store'), $this->data(['name' => '!!!']));

        $this->assertEqualsCanonicalizing(
            ['bola-matcha', 'bola-matcha-2', 'bola-matcha-3', 'produk'],
            Product::pluck('slug')->all(),
        );
    }

    public function test_price_accepts_rupiah_formatting(): void
    {
        $this->actingAs($this->admin());

        foreach (['12000' => 12000, '12.500' => 12500, 'Rp 8.000' => 8000, 'Rp15.000' => 15000, '1.250.000' => 1250000] as $input => $expected) {
            $this->post(route('admin.products.store'), $this->data(['name' => "Harga {$expected}", 'price' => $input]))->assertSessionHasNoErrors();
            $this->assertSame($expected, Product::where('name', "Harga {$expected}")->firstOrFail()->price, "Harga '{$input}'");
        }
    }

    public function test_create_validates_input(): void
    {
        $this->actingAs($this->admin());

        $bad = [
            'name'           => [['name' => ''], ['name' => str_repeat('a', 101)]],
            'category'       => [['category' => 'minuman'], ['category' => '']],
            'price'          => [['price' => '0'], ['price' => '-5000'], ['price' => 'gratis'], ['price' => '12,5'], ['price' => '99999999'], ['price' => '']],
            'description_id' => [['description_id' => ''], ['description_id' => str_repeat('x', 1001)]],
            'description_en' => [['description_en' => ''], ['description_en' => str_repeat('x', 1001)]],
            'badge'          => [['badge' => 'gratis-ongkir']],
        ];

        foreach ($bad as $field => $cases) {
            foreach ($cases as $override) {
                $this->post(route('admin.products.store'), $this->data($override))->assertSessionHasErrors($field);
            }
        }

        $this->assertDatabaseCount('products', 0);
    }

    // ---------- Foto ----------

    public function test_admin_can_upload_a_photo_which_is_shown_in_the_store(): void
    {
        $this->actingAs($this->admin())->post(route('admin.products.store'), $this->data(['image' => $this->png()]))
            ->assertSessionHasNoErrors();

        $product = Product::where('name', 'Bola Matcha')->firstOrFail();
        $this->assertStringStartsWith('images/products/', $product->image);
        $this->assertMatchesRegularExpression('/\.png$/', $product->image);
        Storage::disk('products')->assertExists(basename($product->image));

        $this->get(route('products'))->assertSee(asset($product->image), false);
        $this->get(route('products.show', $product))->assertSee(asset($product->image), false);
    }

    public function test_dangerous_or_oversized_photos_are_rejected(): void
    {
        $this->actingAs($this->admin());
        $png = base64_decode(self::PNG);

        $bad = [
            'terlalu besar (1,5 MB)'    => $this->realFile('besar.png', $png . str_repeat("\0", 1536 * 1024)),
            'teks bernama .png'         => $this->realFile('catatan.png', 'ini bukan gambar'),
            'SVG berisi skrip'          => $this->realFile('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'),
            'SVG menyamar sebagai .png' => $this->realFile('logo.png', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'),
            'PHP menyamar sebagai .jpg' => $this->realFile('shell.jpg', '<?php echo 1;'),
            'PDF'                       => $this->realFile('dokumen.pdf', "%PDF-1.4\n%%EOF"),
        ];

        foreach ($bad as $label => $file) {
            $this->post(route('admin.products.store'), $this->data(['image' => $file]));
            $errors = session('errors');
            $this->assertTrue($errors && $errors->has('image'), "Seharusnya ditolak: {$label}");
        }

        $this->assertDatabaseCount('products', 0);
        $this->assertSame([], Storage::disk('products')->allFiles());
    }

    public function test_uploaded_file_name_never_comes_from_the_client(): void
    {
        $this->actingAs($this->admin())->post(route('admin.products.store'), $this->data(['image' => $this->png('../../shell.php.png')]));

        $files = Storage::disk('products')->allFiles();
        $this->assertCount(1, $files);
        $this->assertDoesNotMatchRegularExpression('/shell|\.\.|php/i', $files[0]);
    }

    // ---------- Ubah ----------

    public function test_admin_can_edit_a_product_and_the_slug_stays_the_same(): void
    {
        $p = $this->makeProduct(['name' => 'Nama Lama', 'slug' => 'nama-lama']);

        $this->actingAs($this->admin())->put(route('admin.products.update', $p), $this->data(['name' => 'Nama Baru Sekali', 'price' => '20.000', 'badge' => 'promo']))
            ->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('status');

        $p->refresh();
        $this->assertSame('Nama Baru Sekali', $p->name);
        $this->assertSame('nama-lama', $p->slug, 'Slug tidak berubah agar tautan lama tetap berlaku');
        $this->assertSame(20000, $p->price);
        $this->assertSame('promo', $p->badge);

        $this->get('/products/nama-lama')->assertOk()->assertSee('Nama Baru Sekali');
    }

    public function test_editing_without_a_new_file_keeps_the_photo_and_a_new_file_replaces_it(): void
    {
        $this->actingAs($this->admin());
        $this->post(route('admin.products.store'), $this->data(['image' => $this->png('a.png')]));
        $p = Product::firstOrFail();
        $old = basename($p->image);

        $this->put(route('admin.products.update', $p), $this->data(['name' => 'Tanpa Ganti Foto']));
        $this->assertSame('images/products/' . $old, $p->fresh()->image);
        Storage::disk('products')->assertExists($old);

        $this->put(route('admin.products.update', $p), $this->data(['image' => $this->png('b.png')]));
        $new = basename($p->fresh()->image);
        $this->assertNotSame($old, $new);
        Storage::disk('products')->assertMissing($old);
        Storage::disk('products')->assertExists($new);
    }

    public function test_photo_can_be_removed(): void
    {
        $this->actingAs($this->admin());
        $this->post(route('admin.products.store'), $this->data(['image' => $this->png()]));
        $p = Product::firstOrFail();
        $file = basename($p->image);

        $this->put(route('admin.products.update', $p), $this->data(['remove_image' => '1']));

        $this->assertNull($p->fresh()->image);
        Storage::disk('products')->assertMissing($file);
    }

    public function test_deactivating_hides_a_product_from_the_store_and_the_cart(): void
    {
        $this->seed(ProductSeeder::class);
        $p = Product::where('slug', 'original-milk')->firstOrFail();
        $customer = User::factory()->create();

        // Sebelum dinonaktifkan: pelanggan punya produk itu di keranjang.
        $this->actingAs($customer)->withCookie('locale', 'id')->withSession(['lumeria_cart' => [$p->id => 2]])
            ->get(route('order'))->assertOk()->assertSee('Original Milk');

        $this->actingAs($this->admin())->put(route('admin.products.update', $p), $this->data([
            'name' => $p->name, 'category' => $p->category, 'price' => $p->price,
            'description_id' => $p->description_id, 'description_en' => $p->description_en, 'is_active' => null,
        ]))->assertSessionHasNoErrors();

        $this->assertFalse($p->fresh()->is_active);
        $this->get(route('products.show', $p))->assertNotFound();
        $this->get(route('products'))->assertDontSee('Original Milk');
        $this->get(route('admin.products.index'))->assertSee('Original Milk')->assertSee('Nonaktif');

        // Setelah dinonaktifkan: produk hilang dari keranjang pelanggan.
        $this->actingAs($customer)->withCookie('locale', 'id')->withSession(['lumeria_cart' => [$p->id => 2]])
            ->get(route('order'))->assertOk()->assertDontSee('Original Milk')->assertSee('Keranjang kamu masih kosong');
    }

    // ---------- Hapus ----------

    public function test_admin_can_delete_a_product_and_its_photo(): void
    {
        $this->actingAs($this->admin());
        $this->post(route('admin.products.store'), $this->data(['image' => $this->png()]));
        $p = Product::firstOrFail();
        $file = basename($p->image);

        $this->delete(route('admin.products.destroy', $p))
            ->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('status');

        $this->assertModelMissing($p);
        Storage::disk('products')->assertMissing($file);
        $this->get('/products/bola-matcha')->assertNotFound();
    }

    public function test_deleting_a_product_keeps_order_history_and_does_not_break_the_cart(): void
    {
        $customer = User::factory()->create();
        $p = $this->makeProduct();
        $order = Order::create([
            'user_id' => $customer->id, 'customer_name' => 'Pembeli', 'customer_phone' => '081234567890',
            'subtotal' => 20000, 'payment_method' => 'cash', 'grand_total' => 20000, 'status' => 'pending',
        ]);
        $item = $order->items()->create(['product_id' => $p->id, 'product_name' => 'Produk Uji', 'price' => 10000, 'quantity' => 2, 'line_total' => 20000]);

        $this->actingAs($this->admin())->delete(route('admin.products.destroy', $p))->assertRedirect();

        $item->refresh();
        $this->assertNull($item->product_id);
        $this->assertSame('Produk Uji', $item->product_name);
        $this->assertSame(10000, $item->price);
        $this->assertModelExists($order);

        // Pelanggan yang keranjangnya masih memuat produk itu tetap bisa membuka halaman pesan (tanpa error).
        $this->actingAs($customer)->withCookie('locale', 'id')->withSession(['lumeria_cart' => [$p->id => 3]])
            ->get(route('order'))->assertOk()->assertSee('Keranjang kamu masih kosong');
    }

    public function test_only_uploaded_photos_are_deleted_never_static_assets_with_the_same_file_name(): void
    {
        // Berkas unggahan bernama sama dengan foto statis milik produk lain.
        Storage::disk('products')->put('sanaya.jpg', 'unggahan-lain');
        $static = public_path('images/team/sanaya.jpg');
        $this->assertFileExists($static);

        $p = $this->makeProduct(['image' => 'images/team/sanaya.jpg']);

        $this->actingAs($this->admin())->delete(route('admin.products.destroy', $p))->assertRedirect();

        $this->assertModelMissing($p);
        $this->assertFileExists($static, 'Foto statis di luar images/products tidak boleh ikut terhapus');
        Storage::disk('products')->assertExists('sanaya.jpg');
    }

    // ---------- Keamanan tampilan ----------

    public function test_product_names_are_escaped_in_the_store_and_the_admin_list(): void
    {
        $this->actingAs($this->admin())->post(route('admin.products.store'), $this->data(['name' => '<script>alert(1)</script>Bola']));

        $this->get(route('products'))->assertDontSee('<script>alert(1)</script>', false)->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;Bola', false);
        $this->get(route('admin.products.index'))->assertDontSee('<script>alert(1)</script>Bola', false);
    }

    public function test_confirm_dialog_text_is_safely_encoded_for_javascript(): void
    {
        $this->makeProduct(['name' => "Bola'); alert('x", 'slug' => 'bola-xss']);

        $html = $this->actingAs($this->admin())->get(route('admin.products.index'))->getContent();

        $this->assertStringNotContainsString("alert('x", $html);
    }
}
