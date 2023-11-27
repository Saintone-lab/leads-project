<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pic;

class PICTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pic = [
            [
                'name_pic' => 'Asep Saepullah',
                'position' => 'Lead Marketing',
                'email_pic' => 'asepsaep@gmail.com',
                'phone_pic' => '08123456789',
            ],
            [
                'name_pic' => 'Hakim Kamilin',
                'position' => 'Sales',
                'email_pic' => 'Hakimkam@gmail.com',
                'phone_pic' => '0894651325',
            ],
            [
                'name_pic' => 'Budi Gunawan',
                'position' => 'CEO',
                'email_pic' => 'pabudinih@gmail.com',
                'phone_pic' => '08215498753',
            ],
        ];
        Pic::insert($pic);
    }
}
