<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
        [
            'name'=> 'Home theatre',
            'price'=> 2000,
            'category'=> 'speaker',
            'description'=> 'Premium home theatre system with surround sound',
            'gallery' => 'speaker.png'
        ],
        [
            'name'=> 'Headset',
            'price'=> 1500,
            'category'=> 'Electronics',
            'description'=> 'High quality noise cancelling headphones',
            'gallery' => 'headphones.png'
        ],
        [
            'name'=> 'Graphics Image',
            'price'=> 1300,
            'category'=> 'graphics',
            'description'=> 'Professional graphic design service',
            'gallery' => 'graphics.jpeg'
        ],
        [
            'name'=> 'Wireless Headphones',
            'price'=> 2500,
            'category'=> 'Electronics',
            'description'=> 'High quality wireless headphones with noise cancellation',
            'gallery' => 'headphones.png'
        ],
        [
            'name'=> 'Smart Watch',
            'price'=> 3000,
            'category'=> 'Electronics',
            'description'=> 'Feature-rich smart watch with health tracking',
            'gallery' => 'smartwatch.png'
        ],
        [
            'name'=> 'Running Shoes',
            'price'=> 1200,
            'category'=> 'Fashion',
            'description'=> 'Comfortable running shoes for daily use',
            'gallery' => 'shoes.png'
        ],
        [
            'name'=> 'Smartphone X',
            'price'=> 3500,
            'category'=> 'Electronics',
            'description'=> 'Latest smartphone with amazing features',
            'gallery' => 'smartphone.png'
        ],
        [
            'name'=> 'Laptop Pro',
            'price'=> 5000,
            'category'=> 'Electronics',
            'description'=> 'High performance laptop for professionals',
            'gallery' => 'laptop.png'
        ]
        ]);
    }
}
