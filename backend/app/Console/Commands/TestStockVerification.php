<?php

namespace App\Console\Commands;

use App\Enums\ProductType;
use App\Enums\RoastLevel;
use App\Models\Category;
use App\Models\CoffeeProfile;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Console\Command;

class TestStockVerification extends Command
{
    protected $signature = 'test:verify-stock';
    protected $description = 'Verifikasi pembuatan kategori, produk, varian, dan mutasi stok ledger';

    public function handle(): int
    {
        $this->info("=== Memulai Tes Kriteria 3 ===");

        // 1. Buat 1 Kategori
        $category = Category::create([
            'name' => 'Single Origin Test',
            'slug' => 'single-origin-test-' . uniqid(),
            'product_type' => ProductType::Coffee,
            'is_active' => true,
        ]);
        $this->info("1. Kategori dibuat: {$category->name} (ID: {$category->id})");

        // 2. Buat 1 Produk Kopi + CoffeeProfile
        $product = Product::create([
            'category_id' => $category->id,
            'type' => ProductType::Coffee,
            'name' => 'Gayo Natural Anaerobic',
            'slug' => 'gayo-natural-anaerobic-' . uniqid(),
            'short_description' => 'Kopi Aceh Gayo proses natural anaerobik.',
            'description' => '<p>Kopi pilihan dengan notes strawberry dan brown sugar.</p>',
            'status' => 'published',
            'is_featured' => true,
        ]);

        $coffeeProfile = CoffeeProfile::create([
            'product_id' => $product->id,
            'process' => 'Natural Anaerobic',
            'roast_level' => RoastLevel::Medium,
            'variety' => 'Ateng Super, Timtim',
            'altitude_masl' => 1500,
            'acidity' => 4,
            'body' => 3,
            'sweetness' => 5,
        ]);
        $this->info("2. Produk kopi dibuat: {$product->name} (ID: {$product->id}) dengan CoffeeProfile (ID: {$coffeeProfile->id})");

        // 3. Buat 2 Varian
        $variant1 = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'GAYO-NAT-200G-' . uniqid(),
            'name' => '200 Gram',
            'price' => 120000,
            'stock' => 0,
            'weight_grams' => 200,
            'is_default' => true,
            'is_active' => true,
        ]);

        $variant2 = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'GAYO-NAT-1KG-' . uniqid(),
            'name' => '1 Kilogram',
            'price' => 480000,
            'stock' => 0,
            'weight_grams' => 1000,
            'is_default' => false,
            'is_active' => true,
        ]);
        $this->info("3. 2 Varian dibuat: {$variant1->sku} dan {$variant2->sku}");

        // 4. Panggil adjustStock(+10) lalu adjustStock(-3) pada variant1
        $this->info("4. Memanggil adjustStock(+10, 'purchase')...");
        $variant1->adjustStock(10, 'purchase', null, null, 'Stok awal masuk');
        $variant1->refresh();
        $this->info("   Stok setelah +10: {$variant1->stock}");

        $this->info("   Memanggil adjustStock(-3, 'sale')...");
        $variant1->adjustStock(-3, 'sale', null, null, 'Penjualan ritel');
        $variant1->refresh();
        $this->info("   Stok setelah -3: {$variant1->stock}");

        // Verifikasi stok akhir harus 7
        if ($variant1->stock !== 7) {
            $this->error("GAGAL: Stok akhir bukan 7, melainkan {$variant1->stock}");
            return Command::FAILURE;
        }

        // Cek ledger inventory_movements
        $movements = InventoryMovement::where('product_variant_id', $variant1->id)->get();
        $this->info("5. Ditemukan {$movements->count()} mutasi di inventory_movements:");
        foreach ($movements as $m) {
            $this->line("   - ID: {$m->id} | Tipe: {$m->type} | Qty: {$m->quantity} | Stock After: {$m->stock_after} | Catatan: {$m->note}");
        }

        if ($movements->count() !== 2) {
            $this->error("GAGAL: Jumlah baris inventory_movements bukan 2.");
            return Command::FAILURE;
        }

        if ($movements[0]->stock_after !== 10 || $movements[1]->stock_after !== 7) {
            $this->error("GAGAL: Nilai stock_after tidak sesuai harapan (10 dan 7).");
            return Command::FAILURE;
        }

        $this->info("SUKSES: Verifikasi kriteria 3 berhasil dengan sempurna!");
        return Command::SUCCESS;
    }
}
