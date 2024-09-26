<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('products')->insert([
            'name' => 'iPhone 6',
            'price' => 600,
            'updated_at' => DB::raw('CURRENT_TIMESTAMP'),
        ]);

        DB::table('products')->insert([
            'name' => 'Note 4',
            'price' => 567,
            'updated_at' => DB::raw('CURRENT_TIMESTAMP'),
        ]);
        DB::table('products')->insert([
            'name' => Str::random(8),
            'price' => rand(100, 1000),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP'),
        ]);
    }
}
