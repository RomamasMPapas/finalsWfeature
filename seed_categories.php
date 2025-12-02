<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Products_category;

echo "Seeding categories from products...\n";

// Get unique categories from products table
$categories = Product::select('category')->distinct()->get();

foreach ($categories as $cat) {
    if (!empty($cat->category)) {
        // Check if category already exists
        $exists = Products_category::where('category', $cat->category)->exists();
        
        if (!$exists) {
            $newCat = new Products_category();
            $newCat->category = $cat->category;
            $newCat->save();
            echo "Added category: " . $cat->category . "\n";
        } else {
            echo "Category already exists: " . $cat->category . "\n";
        }
    }
}

echo "Done!\n";
