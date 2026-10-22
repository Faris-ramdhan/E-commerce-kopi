<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductType;
use App\Enums\RoastLevel;
use App\Models\Address;
use App\Models\Banner;
use App\Models\Category;
use App\Models\CoffeeProfile;
use App\Models\FlavorNote;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Origin;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Voucher;
use App\Models\WholesaleLead;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CoffeeShopDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kategori Produk
        $catCoffee = Category::create([
            'name' => 'Specialty Coffee',
            'slug' => 'specialty-coffee',
            'product_type' => ProductType::Coffee,
            'description' => 'Biji kopi pilihan roasted-to-order dari single origin nusantara.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catTools = Category::create([
            'name' => 'Tools & Dripper',
            'slug' => 'tools-dripper',
            'product_type' => ProductType::Tool,
            'description' => 'Alat seduh manual brew berkualitas untuk pengalaman seduh optimal.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $catApparel = Category::create([
            'name' => 'Apparel',
            'slug' => 'apparel',
            'product_type' => ProductType::Apparel,
            'description' => 'Kaos dan merchandise barista original style.',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $catMerch = Category::create([
            'name' => 'Merchandise & Books',
            'slug' => 'merchandise-books',
            'product_type' => ProductType::Merchandise,
            'description' => 'Tumbler, cangkir keramik, dan buku panduan kopi.',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // 2. Origins (Daerah Asal)
        $originAceh = Origin::create(['name' => 'Aceh Gayo', 'slug' => 'aceh-gayo', 'country' => 'Indonesia', 'region' => 'Takengon, Aceh Tengah']);
        $originFlores = Origin::create(['name' => 'Flores Bajawa', 'slug' => 'flores-bajawa', 'country' => 'Indonesia', 'region' => 'Ngada, NTT']);
        $originToraja = Origin::create(['name' => 'Toraja Sapan', 'slug' => 'toraja-sapan', 'country' => 'Indonesia', 'region' => 'Toraja Utara, Sulsel']);
        $originKerinci = Origin::create(['name' => 'Kerinci Radjea', 'slug' => 'kerinci-radjea', 'country' => 'Indonesia', 'region' => 'Kayu Aro, Jambi']);

        // 3. Flavor Notes
        $notes = [
            'Jasmine' => 'jasmine',
            'Peach' => 'peach',
            'Strawberry' => 'strawberry',
            'Brown Sugar' => 'brown-sugar',
            'Dark Chocolate' => 'dark-chocolate',
            'Citrus' => 'citrus',
            'Bergamot' => 'bergamot',
            'Caramel' => 'caramel',
            'Floral' => 'floral',
            'Nutty' => 'nutty',
        ];
        $flavorModels = [];
        foreach ($notes as $name => $slug) {
            $flavorModels[$slug] = FlavorNote::create(['name' => $name, 'slug' => $slug]);
        }

        // 4. Produk Kopi 1: Aceh Gayo Wine Process
        $prod1 = Product::create([
            'category_id' => $catCoffee->id,
            'type' => ProductType::Coffee,
            'name' => 'Aceh Gayo Wine Process Anaerobic',
            'slug' => 'aceh-gayo-wine-process-anaerobic',
            'short_description' => 'Aroma buah fermentasi manis yang intens dengan sentuhan red wine, strawberry jam, dan brown sugar aftertaste.',
            'description' => '<h3>Eksplorasi Rasa Kopi Gayo Wine</h3><p>Diproses secara cermat melalui fermentasi anaerobik 48 jam yang menghasilkan profil cangkir kompleks, manis berlimpah, dan keasaman juicy yang elegan. Sangat direkomendasikan untuk pour over V60 atau Origami Dripper.</p>',
            'status' => 'published',
            'is_featured' => true,
            'published_at' => now(),
        ]);

        CoffeeProfile::create([
            'product_id' => $prod1->id,
            'origin_id' => $originAceh->id,
            'process' => 'Wine Anaerobic Fermentation',
            'roast_level' => RoastLevel::Light,
            'variety' => 'Ateng Super, Timtim, Typica',
            'producer' => 'Koperasi Baitul Qiradh Baburrayyan',
            'altitude_masl' => 1600,
            'acidity' => 4,
            'body' => 3,
            'sweetness' => 5,
        ]);

        $prod1->flavorNotes()->sync([
            $flavorModels['strawberry']->id,
            $flavorModels['peach']->id,
            $flavorModels['brown-sugar']->id,
        ]);

        ProductImage::create([
            'product_id' => $prod1->id,
            'path' => 'products/gayo-wine.jpg',
            'alt' => 'Kemasan Biji Kopi Aceh Gayo Wine',
            'sort_order' => 1,
            'is_primary' => true,
        ]);

        // Varian Berat & Grind Size untuk Prod 1
        $optWeight = ProductOption::create(['product_id' => $prod1->id, 'name' => 'Kemasan (Berat)', 'sort_order' => 1]);
        $val200g = ProductOptionValue::create(['product_option_id' => $optWeight->id, 'value' => '200g', 'sort_order' => 1]);
        $val500g = ProductOptionValue::create(['product_option_id' => $optWeight->id, 'value' => '500g', 'sort_order' => 2]);
        $val1kg = ProductOptionValue::create(['product_option_id' => $optWeight->id, 'value' => '1000g (1 Kg)', 'sort_order' => 3]);

        $var1_1 = ProductVariant::create([
            'product_id' => $prod1->id,
            'sku' => 'GAYO-WINE-200G',
            'name' => '200 Gram Biji Utuh / Bubuk',
            'price' => 125000,
            'compare_at_price' => 140000,
            'cost_price' => 70000,
            'stock' => 50,
            'weight_grams' => 220,
            'is_default' => true,
            'is_active' => true,
        ]);
        $var1_1->optionValues()->attach($val200g->id);
        $var1_1->movements()->create([
            'type' => 'purchase',
            'quantity' => 50,
            'stock_after' => 50,
            'note' => 'Stok perdana batch roasting #01',
        ]);

        $var1_2 = ProductVariant::create([
            'product_id' => $prod1->id,
            'sku' => 'GAYO-WINE-500G',
            'name' => '500 Gram',
            'price' => 285000,
            'compare_at_price' => 310000,
            'cost_price' => 160000,
            'stock' => 25,
            'weight_grams' => 530,
            'is_default' => false,
            'is_active' => true,
        ]);
        $var1_2->optionValues()->attach($val500g->id);
        $var1_2->movements()->create([
            'type' => 'purchase',
            'quantity' => 25,
            'stock_after' => 25,
            'note' => 'Stok perdana batch roasting #01',
        ]);

        $var1_3 = ProductVariant::create([
            'product_id' => $prod1->id,
            'sku' => 'GAYO-WINE-1KG',
            'name' => '1 Kilogram',
            'price' => 520000,
            'cost_price' => 300000,
            'stock' => 15,
            'weight_grams' => 1050,
            'is_default' => false,
            'is_active' => true,
        ]);
        $var1_3->optionValues()->attach($val1kg->id);
        $var1_3->movements()->create([
            'type' => 'purchase',
            'quantity' => 15,
            'stock_after' => 15,
            'note' => 'Stok perdana batch roasting #01',
        ]);

        // 5. Produk Kopi 2: Flores Bajawa Natural
        $prod2 = Product::create([
            'category_id' => $catCoffee->id,
            'type' => ProductType::Coffee,
            'name' => 'Flores Bajawa Kartika Washed',
            'slug' => 'flores-bajawa-kartika-washed',
            'short_description' => 'Karakter clean cup khas pegunungan Inerie dengan notes citrus, milk chocolate, dan caramel finish.',
            'description' => '<p>Kopi klasik dari dataran tinggi Flores dengan body yang creamy dan balance rasa manis karamel dan cokelat yang nyaman dinikmati setiap pagi.</p>',
            'status' => 'published',
            'is_featured' => true,
            'published_at' => now(),
        ]);

        CoffeeProfile::create([
            'product_id' => $prod2->id,
            'origin_id' => $originFlores->id,
            'process' => 'Fully Washed',
            'roast_level' => RoastLevel::Medium,
            'variety' => 'Kartika, S-795',
            'producer' => 'Kelompok Tani Bajawa',
            'altitude_masl' => 1450,
            'acidity' => 3,
            'body' => 4,
            'sweetness' => 4,
        ]);

        $prod2->flavorNotes()->sync([
            $flavorModels['dark-chocolate']->id,
            $flavorModels['caramel']->id,
            $flavorModels['citrus']->id,
        ]);

        ProductImage::create([
            'product_id' => $prod2->id,
            'path' => 'products/flores-bajawa.jpg',
            'alt' => 'Flores Bajawa Washed Coffee',
            'sort_order' => 1,
            'is_primary' => true,
        ]);

        $var2_1 = ProductVariant::create([
            'product_id' => $prod2->id,
            'sku' => 'FLORES-200G',
            'name' => '200 Gram',
            'price' => 95000,
            'cost_price' => 50000,
            'stock' => 40,
            'weight_grams' => 220,
            'is_default' => true,
            'is_active' => true,
        ]);
        $var2_1->movements()->create([
            'type' => 'purchase',
            'quantity' => 40,
            'stock_after' => 40,
            'note' => 'Initial stock',
        ]);

        // 6. Produk Non-Kopi (Tools)
        $prod3 = Product::create([
            'category_id' => $catTools->id,
            'type' => ProductType::Tool,
            'name' => 'Crystal Glass V60 Dripper 02',
            'slug' => 'crystal-glass-v60-dripper-02',
            'short_description' => 'Dripper kaca tahan panas dengan ulir spiral presisi untuk ekstraksi seduhan manual brew yang konsisten.',
            'description' => '<p>Dibuat dari kaca borosilikat food-grade dengan dudukan kayu akasia elegan yang kokoh di atas server maupun mug.</p>',
            'status' => 'published',
            'is_featured' => false,
            'published_at' => now(),
        ]);

        ProductImage::create([
            'product_id' => $prod3->id,
            'path' => 'products/glass-dripper.jpg',
            'alt' => 'Crystal V60 Dripper',
            'sort_order' => 1,
            'is_primary' => true,
        ]);

        $var3_1 = ProductVariant::create([
            'product_id' => $prod3->id,
            'sku' => 'TOOL-DRIP-02',
            'name' => 'Ukuran 02 (1-4 Cups)',
            'price' => 210000,
            'cost_price' => 120000,
            'stock' => 18,
            'weight_grams' => 450,
            'is_default' => true,
            'is_active' => true,
        ]);
        $var3_1->movements()->create([
            'type' => 'purchase',
            'quantity' => 18,
            'stock_after' => 18,
            'note' => 'Stok restock batch alat',
        ]);

        // 7. Banner Promosi & Hero Storefront
        Banner::create([
            'title' => 'The Art of Slow Pour & Pure Roast',
            'subtitle' => 'Eksplorasi biji kopi single origin pilihan nusantara, disangrai presisi setiap minggu untuk aroma dan rasa terbaik.',
            'image' => 'banners/hero-specialty-coffee.jpg',
            'link_url' => '/shop',
            'cta_label' => 'Jelajahi Koleksi Kopi',
            'placement' => 'home_hero',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'Wholesale & B2B Roastery Partnership',
            'subtitle' => 'Kemitraan pasokan biji kopi roasted untuk coffeeshop, kafe, dan korporat dengan kuota & roasting profile kustom.',
            'image' => 'banners/hero-wholesale.jpg',
            'link_url' => '/wholesale',
            'cta_label' => 'Daftar Kemitraan Wholesale',
            'placement' => 'home_secondary',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 8. Posts / Artikel Journal & Roastery Journey
        $adminUser = User::where('email', 'admin@kopi.test')->first();
        Post::create([
            'author_id' => $adminUser?->id,
            'type' => 'roastery_journey',
            'title' => 'Menemukan Cita Rasa Asli di Lereng Dataran Gayo',
            'slug' => 'menemukan-cita-rasa-asli-di-lereng-dataran-gayo',
            'excerpt' => 'Catatan perjalanan kurasi kami ke Takengon berdialog langsung bersama petani kopi lokal tentang fermentasi wine anaerobic.',
            'content' => '<p>Di ketinggian lebih dari 1.500 mdpl, kami melihat sendiri dedikasi petani dalam memilah ceri kopi merah sempurna (red cherry selection) dan mengontrol temperatur fermentasi...</p>',
            'cover_image' => 'posts/gayo-journey.jpg',
            'status' => 'published',
            'published_at' => now()->subDays(5),
        ]);

        Post::create([
            'author_id' => $adminUser?->id,
            'type' => 'journal',
            'title' => 'Panduan Seduh V60 di Rumah untuk Pemula',
            'slug' => 'panduan-seduh-v60-di-rumah-untuk-pemula',
            'excerpt' => 'Ratio air 1:15, suhu 92°C, dan teknik pouring 3 tahap untuk menghasilkan seduhan manis tanpa rasa pahit berlebih.',
            'content' => '<p>Menyeduh kopi manual bukanlah ritual rumit. Dengan 3 kunci dasar (rasio, suhu air, dan grind size), Anda bisa menikmati secangkir kopi segar berkualitas kafe setiap pagi...</p>',
            'cover_image' => 'posts/v60-guide.jpg',
            'status' => 'published',
            'published_at' => now()->subDays(2),
        ]);

        // 9. Voucher Diskon
        Voucher::create([
            'code' => 'KOPIBARU10',
            'name' => 'Diskon Pembelian Pertama 10%',
            'type' => 'percentage',
            'value' => 10,
            'max_discount' => 30000,
            'min_purchase' => 100000,
            'usage_limit' => 500,
            'is_active' => true,
        ]);

        Voucher::create([
            'code' => 'GRATISONGKIR',
            'name' => 'Subsidi Gratis Ongkir Se-Indonesia',
            'type' => 'free_shipping',
            'value' => 20000,
            'min_purchase' => 150000,
            'usage_limit' => 200,
            'is_active' => true,
        ]);

        // 10. Sample Wholesale Lead
        WholesaleLead::create([
            'business_name' => 'Senja Coffee & Eatery',
            'business_type' => 'Coffeeshop Ritel',
            'contact_name' => 'Dimas Pratama',
            'email' => 'dimas@senjacoffee.id',
            'phone' => '081399887766',
            'city' => 'Bandung',
            'estimated_monthly_kg' => 45,
            'message' => 'Kami membutuhkan house blend kopi susu kekinian dan 1 single origin filter pour over untuk 2 cabang kafe kami di Bandung.',
            'status' => 'new',
        ]);

        // 11. Sample Order untuk Demonstrasi Admin
        $customerUser = User::where('email', 'customer@kopi.test')->first();
        if ($customerUser) {
            $address = Address::create([
                'user_id' => $customerUser->id,
                'label' => 'Rumah',
                'recipient_name' => 'Budi Santoso',
                'phone' => '081234567893',
                'province' => 'Jawa Barat',
                'city' => 'Kota Bandung',
                'district' => 'Coblong',
                'postal_code' => '40132',
                'address_line' => 'Jl. Dago Asri No. 12, Kel. Dago',
                'is_default' => true,
            ]);

            $order = Order::create([
                'order_number' => 'KOPI-' . date('Ymd') . '-0001',
                'user_id' => $customerUser->id,
                'status' => OrderStatus::Processing,
                'payment_status' => PaymentStatus::Paid,
                'shipping_name' => $address->recipient_name,
                'shipping_phone' => $address->phone,
                'shipping_province' => $address->province,
                'shipping_city' => $address->city,
                'shipping_district' => $address->district,
                'shipping_postal_code' => $address->postal_code,
                'shipping_address' => $address->address_line,
                'subtotal' => 220000,
                'discount_total' => 22000,
                'shipping_cost' => 15000,
                'grand_total' => 213000,
                'voucher_code' => 'KOPIBARU10',
                'courier' => 'JNE',
                'courier_service' => 'REG',
                'total_weight_grams' => 440,
                'tracking_number' => 'JNE88299100234',
                'customer_note' => 'Mohon digiling medium untuk seduh V60.',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $var1_1->id,
                'product_name' => $prod1->name,
                'variant_name' => $var1_1->name,
                'sku' => $var1_1->sku,
                'price' => 125000,
                'quantity' => 1,
                'weight_grams' => 220,
                'subtotal' => 125000,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $var2_1->id,
                'product_name' => $prod2->name,
                'variant_name' => $var2_1->name,
                'sku' => $var2_1->sku,
                'price' => 95000,
                'quantity' => 1,
                'weight_grams' => 220,
                'subtotal' => 95000,
            ]);

            Payment::create([
                'order_id' => $order->id,
                'gateway' => 'midtrans',
                'method' => 'qris',
                'external_id' => 'MID-' . Str::random(10),
                'status' => 'paid',
                'amount' => 213000,
                'paid_at' => now(),
            ]);

            $order->histories()->create([
                'user_id' => $adminUser?->id,
                'from_status' => OrderStatus::Paid->value,
                'to_status' => OrderStatus::Processing->value,
                'note' => 'Pembayaran terverifikasi QRIS. Pesanan sedang diproses roaster & dikemas.',
            ]);
        }
    }
}
