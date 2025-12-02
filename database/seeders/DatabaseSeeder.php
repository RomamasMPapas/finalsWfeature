<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed Admin
        DB::table('admins')->insert([
            'name' => 'roma',
            'email' => 'roma', // User asked for name=roma, using it as email for login
            'password' => Hash::make('roma'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed Categories
        $categories = ['Electronics', 'Fashion', 'Home', 'Toys'];
        foreach ($categories as $category) {
            DB::table('categories')->insertOrIgnore([
                'name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed Products
        $products = [
            [
                'name' => 'Smartphone X',
                'price' => '999',
                'category' => 'Electronics',
                'description' => 'Latest smartphone with amazing features.',
                'gallery' => 'mobile.jpg',
            ],
            [
                'name' => 'Laptop Pro',
                'price' => '1299',
                'category' => 'Electronics',
                'description' => 'High performance laptop for professionals.',
                'gallery' => 'laptop.jpg',
            ],
            [
                'name' => 'Designer T-Shirt',
                'price' => '49',
                'category' => 'Fashion',
                'description' => 'Cotton t-shirt with unique design.',
                'gallery' => 'shirt.jpg',
            ],
            [
                'name' => 'Smart Watch',
                'price' => '199',
                'category' => 'Electronics',
                'description' => 'Track your fitness and notifications.',
                'gallery' => 'watch.jpg',
            ],
             [
                'name' => 'Headphones',
                'price' => '149',
                'category' => 'Electronics',
                'description' => 'Noise cancelling headphones.',
                'gallery' => 'headphones.jpg',
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert(array_merge($product, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
