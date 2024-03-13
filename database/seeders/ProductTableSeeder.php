<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = [
            [
                'commodity' => 'W962',
                'description' => 'Oil Filter',
                'go' => 'Oem',
                'category' => 'Consumable Part',
                'dimension' => '200x100x150',
                'stock' => '10',
                'unit' => 'pail',
                'note' => '-',
            ],
            [
                'commodity' => 'W940',
                'description' => 'Oil Filter',
                'go' => 'Oem',
                'category' => 'Consumable Part',
                'dimension' => '170x100x150',
                'stock' => '4',
                'unit' => 'pail',
                'note' => '-',
            ],
        ];
        Product::insert($product);
    }
}
