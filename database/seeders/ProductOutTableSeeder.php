<?php

namespace Database\Seeders;

use App\Models\ProductOut;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductOutTableSeeder extends Seeder
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
                'invoice' => 'PIN002/14/III/23',
                'detail_client' => 'Bpk Asep',
                'note' => '-',
                "date"=> \Carbon\Carbon::today()->format('Y-m-d H:i:s'),
                'shipping' => '500000',
                'total' => '5000000',
            ],
            [
                'invoice' => 'PIN005/14/III/23',
                'detail_client' => 'Bpk Raden Dewi',
                'note' => '-',
                "date"=> \Carbon\Carbon::today()->format('Y-m-d H:i:s'),
                'shipping' => '250000',
                'total' => '2500000',
            ],
        ];
        ProductOut::insert($product);
    }
}
