<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create a test customer account
$user = new User();
$user->first_name = 'Rob';
$user->last_name = 'Martin';
$user->email = 'customer@test.com';
$user->password = Hash::make('customer123');
$user->phone = '09123456789';
$user->address = '123 Test Street, Test City';
$user->save();

echo "Customer account created successfully!\n";
echo "Email: customer@test.com\n";
echo "Password: customer123\n";
