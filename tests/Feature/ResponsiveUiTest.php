<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponsiveUiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductSeeder::class);
    }

    public function test_navbar_collapses_below_xl_and_page_has_skip_link(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('navbar-expand-xl', false)
            ->assertSee('href="#main"', false)
            ->assertSee('id="main"', false)
            ->assertSee('name="viewport"', false);
    }

    public function test_floating_cart_button_shows_when_cart_has_items(): void
    {
        $id = Product::firstOrFail()->id;

        $this->loginUser()->withSession(['lumeria_cart' => [$id => 2]])
            ->get('/products')
            ->assertSee('class="cart-fab', false);
    }

    public function test_floating_cart_button_is_hidden_on_the_order_page(): void
    {
        $id = Product::firstOrFail()->id;

        $this->loginUser()->withSession(['lumeria_cart' => [$id => 2]])
            ->get('/order')
            ->assertDontSee('class="cart-fab', false);
    }

    public function test_floating_cart_button_is_hidden_when_cart_is_empty(): void
    {
        $this->get('/products')->assertDontSee('class="cart-fab', false);
    }

    public function test_add_to_cart_without_referer_falls_back_to_products(): void
    {
        $id = Product::firstOrFail()->id;

        $this->post('/cart', ['product_id' => $id])
            ->assertRedirectContains('/products#product-' . $id);
    }

    public function test_status_toast_links_to_cart_after_adding(): void
    {
        $this->loginUser()->withSession(['status' => 'Item ditambahkan.', 'cart_link' => true])
            ->get('/products')
            ->assertSee('Lihat keranjang');
    }

    public function test_cart_and_plan_tables_use_labelled_stacked_layout(): void
    {
        $id = Product::firstOrFail()->id;

        $this->loginUser()->withSession(['lumeria_cart' => [$id => 1]])
            ->get('/order')
            ->assertOk()
            ->assertSee('align-middle stack-table', false)
            ->assertSee('data-label="Produk"', false)
            ->assertSee('data-label="Total bayar"', false)
            ->assertSee('autocomplete="tel"', false)
            ->assertSee('autocomplete="name"', false);
    }

    public function test_checkout_errors_are_shown_next_to_fields(): void
    {
        $id = Product::firstOrFail()->id;

        $this->loginUser()->withSession(['lumeria_cart' => [$id => 1]])
            ->from('/order')
            ->post('/order', ['payment_method' => 'cash'])
            ->assertRedirect('/order')
            ->assertSessionHasErrors(['customer_name', 'customer_phone']);

        $this->get('/order')->assertSee('invalid-feedback', false);
    }

    public function test_views_lang_files_and_seeders_contain_no_emoji(): void
    {
        $pattern = '/[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{FE0F}]/u';
        $found   = [];

        foreach ([resource_path('views'), lang_path(), database_path('seeders')] as $dir) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($files as $file) {
                if (! $file->isFile() || ! in_array($file->getExtension(), ['php', 'json'], true)) {
                    continue;
                }

                if (preg_match($pattern, file_get_contents($file->getPathname()))) {
                    $found[] = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());
                }
            }
        }

        $this->assertSame([], $found, 'Emoji ditemukan di: ' . implode(', ', $found));
    }
}