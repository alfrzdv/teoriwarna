<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductStock;
use App\Models\Category;

// Delete all existing products and their stock transactions
echo "Deleting all existing products..." . PHP_EOL;
\Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
ProductStock::truncate();
ProductImage::truncate();
Product::truncate();
\Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
echo "Done!" . PHP_EOL . PHP_EOL;

// Get Alat Seni category
$category = Category::where('name', 'Alat Seni')->first();

if (!$category) {
    echo "Category 'Alat Seni' not found!" . PHP_EOL;
    exit(1);
}

$products = [
    // Pensil Warna
    ['name' => 'Pensil Warna Joyko 24 Warna', 'slug' => 'pensil-warna-joyko-24', 'description' => 'Pensil warna set 24 warna dari Joyko dengan kualitas terpercaya. Warna cerah dan tahan lama, cocok untuk anak sekolah.', 'price' => 45000, 'sku' => 'PW-JOY-24', 'status' => 'active'],
    ['name' => 'Pensil Warna Deli 36 Warna', 'slug' => 'pensil-warna-deli-36', 'description' => 'Pensil warna profesional set 36 warna dari Deli. Untuk pengguna yang lebih pro dengan pilihan warna lebih banyak.', 'price' => 89000, 'sku' => 'PW-DELI-36', 'status' => 'active'],

    // Crayon
    ['name' => 'Crayon 12 Warna', 'slug' => 'crayon-12', 'description' => 'Crayon set 12 warna favorit anak TK-SD. Super murah dan mudah digunakan untuk mewarnai.', 'price' => 15000, 'sku' => 'CRY-12', 'status' => 'active'],

    // Oil Pastel
    ['name' => 'Oil Pastel 12 Warna', 'slug' => 'oil-pastel-12', 'description' => 'Oil pastel set 12 warna dengan tekstur lembut. Ideal untuk teknik melukis dengan hasil warna pekat.', 'price' => 35000, 'sku' => 'OP-12', 'status' => 'active'],
    ['name' => 'Oil Pastel 24 Warna', 'slug' => 'oil-pastel-24', 'description' => 'Oil pastel set 24 warna lengkap. Pilihan warna lebih banyak untuk karya seni yang lebih kompleks.', 'price' => 65000, 'sku' => 'OP-24', 'status' => 'active'],

    // Soft Pastel
    ['name' => 'Soft Pastel 12 Warna', 'slug' => 'soft-pastel-12', 'description' => 'Soft pastel set 12 warna dengan tekstur halus. Mudah di-blend untuk efek gradasi.', 'price' => 55000, 'sku' => 'SP-12', 'status' => 'active'],
    ['name' => 'Soft Pastel 24 Warna', 'slug' => 'soft-pastel-24', 'description' => 'Soft pastel set 24 warna untuk efek gradasi halus. Perfect untuk landscape dan portrait art.', 'price' => 95000, 'sku' => 'SP-24', 'status' => 'active'],

    // Cat Air
    ['name' => 'Cat Air 12 Warna Tube', 'slug' => 'cat-air-12-tube', 'description' => 'Cat air set 12 warna dalam tube. Kualitas bagus untuk pemula hingga menengah, warna vibrante.', 'price' => 75000, 'sku' => 'CA-TUBE-12', 'status' => 'active'],
    ['name' => 'Cat Air 24 Warna', 'slug' => 'cat-air-24', 'description' => 'Cat air set 24 warna paling laris untuk pemula. Dilengkapi palette dalam kemasan praktis.', 'price' => 125000, 'sku' => 'CA-24', 'status' => 'active'],
    ['name' => 'Cat Air Paket Beginner Set', 'slug' => 'cat-air-beginner-set', 'description' => 'Paket lengkap cat air dengan palette, kuas, dan mixing tray. All-in-one untuk pemula.', 'price' => 150000, 'sku' => 'CA-BEGINNER', 'status' => 'active'],

    // Brush Pen
    ['name' => 'Brush Pen 24 Warna Water-Based', 'slug' => 'brush-pen-24', 'description' => 'Brush pen set 24 warna water-based. Perfect untuk hand lettering dan coloring book.', 'price' => 85000, 'sku' => 'BP-24', 'status' => 'active'],
    ['name' => 'Watercolor Brush Pen 30 Warna', 'slug' => 'watercolor-brush-pen-30', 'description' => 'Watercolor brush pen set 30 warna. Tren terbaru untuk lukis mudah tanpa kuas terpisah.', 'price' => 165000, 'sku' => 'WBP-30', 'status' => 'active'],

    // Marker
    ['name' => 'Alcohol Marker 36 Warna Dual Tip', 'slug' => 'alcohol-marker-36', 'description' => 'Alcohol marker set 36 warna dengan dual tip (fine & brush). Untuk illustration dan coloring profesional.', 'price' => 285000, 'sku' => 'AM-36', 'status' => 'active'],
    ['name' => 'Washable Marker 24 Warna', 'slug' => 'washable-marker-24', 'description' => 'Washable marker set 24 warna aman untuk anak kecil. Mudah dibersihkan dari pakaian dan kulit.', 'price' => 55000, 'sku' => 'WM-24', 'status' => 'active'],
    ['name' => 'Acrylic Paint Marker 24 Warna', 'slug' => 'acrylic-marker-24', 'description' => 'Acrylic paint marker set 24 warna. Bisa digunakan di kertas, kayu, kain, bahkan kaca.', 'price' => 195000, 'sku' => 'APM-24', 'status' => 'active'],

    // Fine Liner
    ['name' => 'Fine Liner Hitam Set (0.05-0.8mm)', 'slug' => 'fine-liner-black-set', 'description' => 'Fine liner set hitam berbagai ukuran 0.05-0.8mm. Waterproof untuk sketsa dan outlining profesional.', 'price' => 65000, 'sku' => 'FL-BLK', 'status' => 'active'],
    ['name' => 'Fine Liner Warna 12 Set', 'slug' => 'fine-liner-color-12', 'description' => 'Fine liner warna set 12 pcs. Perfect untuk doodle, journaling, dan bullet journal.', 'price' => 75000, 'sku' => 'FL-COL-12', 'status' => 'active'],

    // Gel Pen
    ['name' => 'Gel Pen 48 Warna Multicolor', 'slug' => 'gel-pen-48', 'description' => 'Gel pen set 48 warna termasuk glitter dan metallic. Lengkap untuk journaling dan coloring.', 'price' => 95000, 'sku' => 'GP-48', 'status' => 'active'],
    ['name' => 'Gel Pen 36 Warna dengan Refill', 'slug' => 'gel-pen-36-refill', 'description' => 'Gel pen set 36 warna dengan refill tambahan. Cocok untuk journaling panjang dan hemat.', 'price' => 110000, 'sku' => 'GP-36-REF', 'status' => 'active'],

    // Kertas
    ['name' => 'Kertas Watercolor A5 Cold Press', 'slug' => 'kertas-wc-a5', 'description' => 'Kertas watercolor pad A5 cold press 20 lembar. Tebal 200gsm, cocok untuk cat air.', 'price' => 45000, 'sku' => 'KW-A5', 'status' => 'active'],
    ['name' => 'Kertas Watercolor A4 Cold Press', 'slug' => 'kertas-wc-a4', 'description' => 'Kertas watercolor pad A4 cold press 20 lembar. Ukuran lebih besar untuk lukis detail.', 'price' => 75000, 'sku' => 'KW-A4', 'status' => 'active'],

    // Sketchbook
    ['name' => 'Sketchbook A5 100 Lembar Polos', 'slug' => 'sketchbook-a5-100', 'description' => 'Sketchbook A5 tebal 100 lembar polos. Kertas 120gsm cocok untuk pensil dan pen.', 'price' => 55000, 'sku' => 'SK-A5-100', 'status' => 'active'],
    ['name' => 'Sketchbook A4 80 Lembar Polos', 'slug' => 'sketchbook-a4-80', 'description' => 'Sketchbook A4 tebal 80 lembar polos. Cocok untuk sketsa lebih besar dan detail.', 'price' => 85000, 'sku' => 'SK-A4-80', 'status' => 'active'],

    // Drawing Tablet
    ['name' => 'Huion Inspiroy H640P Drawing Tablet', 'slug' => 'huion-h640p', 'description' => 'Drawing tablet tanpa layar entry-level ukuran kecil. 8192 pressure levels, battery-free pen.', 'price' => 650000, 'sku' => 'DT-H640P', 'status' => 'active'],
    ['name' => 'XP-Pen Deco 01 V2 Medium', 'slug' => 'xppen-deco-01', 'description' => 'Drawing tablet medium size dengan 8 shortcut keys. Ideal untuk digital illustration pemula.', 'price' => 750000, 'sku' => 'DT-DECO01', 'status' => 'active'],
    ['name' => 'XP-Pen Artist 13.3 Pen Display', 'slug' => 'xppen-artist-13', 'description' => 'Drawing tablet dengan layar 13.3 inch Full HD. Mid-range terbaik untuk digital artist.', 'price' => 3500000, 'sku' => 'PD-ART13', 'status' => 'active'],
    ['name' => 'Huion Kamvas 16 Pen Display', 'slug' => 'huion-kamvas-16', 'description' => 'Pen display 15.6 inch dengan anti-glare screen. Professional level dengan harga terjangkau.', 'price' => 4200000, 'sku' => 'PD-KAM16', 'status' => 'active'],

    // Stylus
    ['name' => 'Apple Pencil Compatible Stylus', 'slug' => 'stylus-ipad-pencil', 'description' => 'Stylus pressure-sensitive compatible dengan iPad. Third-party alternatif Apple Pencil hemat.', 'price' => 350000, 'sku' => 'STY-IPAD', 'status' => 'active'],
    ['name' => 'Drawing Glove 2-Finger', 'slug' => 'drawing-glove-2finger', 'description' => 'Drawing glove anti-fouling 2 jari. Hindari palm rejection error di tablet digital.', 'price' => 25000, 'sku' => 'GLV-2F', 'status' => 'active'],

    // Software & Bundle
    ['name' => 'Clip Studio Paint PRO License', 'slug' => 'clip-studio-paint-pro', 'description' => 'Lisensi software digital art Clip Studio Paint PRO. Industry standard untuk manga dan illustration.', 'price' => 550000, 'sku' => 'SW-CSP-PRO', 'status' => 'active'],
    ['name' => 'Paket Beginner Digital Art Bundle', 'slug' => 'beginner-digital-bundle', 'description' => 'Paket lengkap: drawing tablet + stylus + glove + screen protector matte. All you need to start.', 'price' => 850000, 'sku' => 'BDL-BEGINNER', 'status' => 'active']
];

echo "Creating products..." . PHP_EOL . PHP_EOL;

foreach ($products as $productData) {
    $product = Product::create(array_merge($productData, ['category_id' => $category->id]));
    $stock = rand(10, 50);
    $product->addStock($stock, 'Initial stock');
    echo $product->name . ' (Stock: ' . $stock . ')' . PHP_EOL;
}

echo PHP_EOL . "Total: " . count($products) . " products created!" . PHP_EOL;
