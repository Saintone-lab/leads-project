<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Issues;

class IssuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $issues = [
            [
                'name' => 'Send Introduction'
            ],
            [
                'name' => 'Send Quote'
            ],
            [
                'name' => 'Done PO'
            ],
            [
                'name' => 'Loss'
            ],
        ];
        Issues::insert($issues);
    }
}
