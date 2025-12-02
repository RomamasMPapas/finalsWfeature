<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get all products
$products = App\Models\Product::all(['id', 'name', 'gallery']);

echo "Current Products:\n";
echo "================\n";
foreach ($products as $product) {
    echo "ID: {$product->id} | Name: {$product->name} | Image: {$product->gallery}\n";
}

// Update products with missing or placeholder images
$updates = [
    ['name' => 'Home theatre', 'gallery' => 'speaker.png'],
    ['name' => 'Headset', 'gallery' => 'headphones.png'],
    ['name' => 'Smartphone X', 'gallery' => 'smartphone.png'],
    ['name' => 'Laptop Pro', 'gallery' => 'laptop.png'],
];

echo "\nUpdating products...\n";
foreach ($updates as $update) {
    $product = App\Models\Product::where('name', $update['name'])->first();
    if ($product) {
        $product->gallery = $update['gallery'];
        $product->save();
        echo "Updated: {$product->name} -> {$update['gallery']}\n";
    }
}

echo "\nDone!\n";
