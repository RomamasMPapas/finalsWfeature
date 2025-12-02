<?php

// Script to find unused product images

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\ArchivedProduct;

// Get all images from public/assets/images
$imagesPath = public_path('assets/images');
$allFiles = array_diff(scandir($imagesPath), array('.', '..'));

// Get images used in products table
$usedInProducts = Product::pluck('gallery')->toArray();

// Get images used in archived_products table
$usedInArchived = ArchivedProduct::pluck('gallery')->toArray();

// Combine both lists
$usedImages = array_merge($usedInProducts, $usedInArchived);
$usedImages = array_filter($usedImages); // Remove empty values
$usedImages = array_unique($usedImages); // Remove duplicates

// Find unused files
$unusedFiles = array_diff($allFiles, $usedImages);

// Calculate file sizes
$unusedFilesWithSize = [];
$totalSize = 0;

foreach ($unusedFiles as $file) {
    $filePath = $imagesPath . DIRECTORY_SEPARATOR . $file;
    if (is_file($filePath)) {
        $size = filesize($filePath);
        $totalSize += $size;
        $unusedFilesWithSize[] = [
            'name' => $file,
            'size' => $size,
            'size_readable' => formatBytes($size)
        ];
    }
}

// Helper function to format bytes
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

// Build output
$output = "\n=== UNUSED PRODUCT IMAGES REPORT ===\n\n";
$output .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";
$output .= "SUMMARY:\n";
$output .= "--------\n";
$output .= "Total files in directory: " . count($allFiles) . "\n";
$output .= "Files used in products: " . count($usedInProducts) . "\n";
$output .= "Files used in archived: " . count($usedInArchived) . "\n";
$output .= "Total USED files: " . count($usedImages) . "\n";
$output .= "Total UNUSED files: " . count($unusedFilesWithSize) . "\n";
$output .= "Space wasted: " . formatBytes($totalSize) . "\n\n";

if (count($unusedFilesWithSize) > 0) {
    $output .= "UNUSED FILES:\n";
    $output .= "-------------\n";
    
    foreach ($unusedFilesWithSize as $file) {
        $output .= sprintf("- %s (%s)\n", $file['name'], $file['size_readable']);
    }
    
    $output .= "\n";
    $output .= "LOCATION:\n";
    $output .= "---------\n";
    $output .= $imagesPath . "\n\n";
    
    $output .= "USED FILES (for reference):\n";
    $output .= "---------------------------\n";
    foreach ($usedImages as $used) {
        $output .= "- " . $used . "\n";
    }
} else {
    $output .= "✓ All image files are being used!\n";
}

$output .= "\n";

// Save to file
file_put_contents(__DIR__ . '/UNUSED_IMAGES_REPORT.txt', $output);

// Also echo to console
echo $output;
echo "Report saved to: UNUSED_IMAGES_REPORT.txt\n";
