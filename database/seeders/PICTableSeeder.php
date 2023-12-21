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
                'id_client' => '1',
                'name_pic' => 'Asep Saepullah',
                'position' => 'Lead Marketing',
                'email_pic' => 'asepsaep@gmail.com',
                'phone_pic' => '08123456789',
                'area' => 'Bandung',
                'machine' => 'Kaeser',
            ],
            [
                'id_client' => '1',
                'name_pic' => 'Gunawan',
                'position' => 'Manager Pruchasing',
                'email_pic' => 'gunawan@gmail.com',
                'phone_pic' => '08126478789',
                'area' => 'Cimahi',
                'machine' => 'Atlas Copco',
            ],
            [
                'id_client' => '2',
                'name_pic' => 'Hakim Kamilin',
                'position' => 'Sales',
                'email_pic' => 'Hakimkam@gmail.com',
                'phone_pic' => '0894651325',
                'area' => 'Jakarta',
                'machine' => 'Kaeser',
            ],
            [
                'id_client' => '3',
                'name_pic' => 'Budi Gunawan',
                'position' => 'CEO',
                'email_pic' => 'pabudinih@gmail.com',
                'phone_pic' => '08215498753',
                'area' => 'Bandung',
                'machine' => 'Atlas Copco',
            ],
        ];
        Pic::insert($pic);
    }
}
