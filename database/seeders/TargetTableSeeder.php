<?php

namespace Database\Seeders;

use App\Models\Target;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TargetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $target = [
            [
                'id_sales' => '1',
                'dc' => '600',
                'intro' => '40',
                'quote' => '100',
                'po' => '20',
                'total' => '150000000',
            ],
            [
                'id_sales' => '2',
                'dc' => '600',
                'intro' => '40',
                'quote' => '40',
                'po' => '20',
                'total' => '150000000',
            ],
            [
                'id_sales' => '3',
                'dc' => '600',
                'intro' => '40',
                'quote' => '40',
                'po' => '20',
                'total' => '150000000',
            ],
            [
                'id_sales' => '4',
                'dc' => '600',
                'intro' => '40',
                'quote' => '40',
                'po' => '20',
                'total' => '350000000',
            ],
            [
                'id_sales' => '13',
                'dc' => '600',
                'intro' => '40',
                'quote' => '40',
                'po' => '20',
                'total' => '150000000',
            ],
            [
                'id_sales' => '14',
                'dc' => '600',
                'intro' => '40',
                'quote' => '40',
                'po' => '20',
                'total' => '150000000',
            ],
            [
                'id_sales' => '16',
                'dc' => '600',
                'intro' => '40',
                'quote' => '40',
                'po' => '20',
                'total' => '350000000',
            ],
        ];
        Target::insert($target);
    }
}
