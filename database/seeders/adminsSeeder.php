<?php

namespace Database\Seeders;
use Hash;
use Illuminate\Database\Seeder;
use DB;
class adminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->truncate();
        DB::table('admins')->insert([
            [
                'name' => 'Oladayo Akorede',
                'email' => 'oladayoahmod112@gmail.com',
                'password' => Hash::make('olami'),
                'image' => 'default.jpg'
            ],
            [
                'name' => 'Rob Malik',
                'email' => 'robmalik@gmail.com',
                'password' => Hash::make('robmalik'),
                'image' => 'default.jpg'
            ]
        ]);
    }
}
