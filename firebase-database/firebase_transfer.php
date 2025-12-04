<?php
// firebase_transfer.php
// Run this script to copy all SQLite data into Firebase Firestore.
// Make sure you have installed the Firebase PHP SDK via:
//   composer require kreait/firebase-php ^6.0
// and placed your Firebase service account JSON at the path defined in .env
// (FIREBASE_SERVICE_ACCOUNT) or at 'firebase_service_account.json' in the project root.

require __DIR__.'/../vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

// ---------------------------------------------------------------------
// 1️⃣ Load SQLite database (the same DB Laravel uses)
$sqlitePath = __DIR__.'/../database/database.sqlite';
if (!file_exists($sqlitePath)) {
    die("❌ SQLite file not found at $sqlitePath\n");
}
$pdo = new PDO("sqlite:$sqlitePath");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ---------------------------------------------------------------------
// 2️⃣ Initialise Firestore client
$serviceAccountPath = getenv('FIREBASE_SERVICE_ACCOUNT') ?: __DIR__.'/../firebase_service_account.json';
if (!file_exists($serviceAccountPath)) {
    die("❌ Service‑account JSON not found at $serviceAccountPath\n");
}

$factory = (new Factory)
    ->withServiceAccount($serviceAccountPath);

$firestore = $factory->createFirestore();
$db = $firestore->database();

// ---------------------------------------------------------------------
// Helper: migrate a SQLite table to a Firestore collection
function writeCollection(PDO $pdo, string $table, string $collectionName, $db)
{
    echo "🔄 Migrating $table → $collectionName ...\n";
    $stmt = $pdo->query("SELECT * FROM $table");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        $docId = $row['id'];
        unset($row['id']);
        $row['legacy_id'] = $docId; // keep original id for reference
        // Decode JSON strings if any
        foreach ($row as $k => $v) {
            if (is_string($v) && (strpos($v, '{') === 0 || strpos($v, '[') === 0)) {
                $decoded = json_decode($v, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $row[$k] = $decoded;
                }
            }
        }
        $db->collection($collectionName)->document((string)$docId)->set($row);
    }
    echo "✅ $table migrated (".count($rows)." records).\n";
}

// ---------------------------------------------------------------------
// 3️⃣ Define tables to migrate (SQLite table => Firestore collection)
$tables = [
    'products'           => 'products',
    'product_categories' => 'categories',
    'orders'             => 'orders',
    'carts'              => 'carts',
    'users'              => 'users',
    'archived_products'  => 'archived_products',
];

foreach ($tables as $sqliteTable => $firestoreColl) {
    writeCollection($pdo, $sqliteTable, $firestoreColl, $db);
}

echo "🎉 All data transferred to Firestore!\n";
?>
