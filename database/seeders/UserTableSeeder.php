<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'name' => 'Yusuf Herdiana',
                'phone' => '-',
                'email' => 'sales@reftech.id',
                'password' => Hash::make('sales123'),
                'code' => 'YH',
                'area' => 'Bandung',
                'role' => 'Sales',
                'image' => 'asset/profile/profile.jpg',
                'sign' => NULL,
            ],
            [
                'name' => 'Regita Dwi Melinda',
                'phone' => '-',
                'email' => 'regita@reftech.id',
                'password' => Hash::make('sales123'),
                'code' => 'RM',
                'area' => 'Bandung',
                'role' => 'Sales',
                'image' => 'asset/profile/profile.jpg',
                'sign' => NULL,
            ],
            [
                'name' => 'Rifan Fahli',
                'phone' => '-',
                'email' => 'rifan@reftech.id',
                'password' => Hash::make('sales123'),
                'code' => 'RF',
                'area' => 'Bandung',
                'role' => 'Sales',
                'image' => 'asset/profile/profile.jpg',
                'sign' => NULL,
            ],
            [
                'name' => 'Yolan Yolanda',
                'phone' => '-',
                'email' => 'support@reftech.id',
                'password' => Hash::make('sales123'),
                'code' => 'CRM',
                'area' => 'Bandung',
                'role' => 'Sales',
                'image' => 'asset/profile/profile.jpg',
                'sign' => NULL,
            ],
            [
                'name' => 'Angel Irene',
                'phone' => '-',
                'email' => 'irene@reftech.id',
                'password' => Hash::make('admin123'),
                'code' => 'ADM-I',
                'area' => 'Bandung',
                'role' => 'Admin',
                'image' => 'asset/profile/profile.jpg',
                'sign' => NULL,
            ],
            [
                'name' => 'Arief Rachman',
                'phone' => '-',
                'email' => 'arief@reftech.id',
                'password' => Hash::make('admin123'),
                'code' => 'ADM-A',
                'area' => 'Bandung',
                'role' => 'Admin',
                'image' => 'asset/profile/profile.jpg',
                'sign' => NULL,
            ],
            [
                'name' => 'Admin Ref',
                'phone' => '-',
                'email' => 'admin@reftech.id',
                'password' => Hash::make('admin123'),
                'code' => 'ADM-R',
                'area' => 'Bandung',
                'role' => 'Admin',
                'image' => 'asset/profile/profile.jpg',
                'sign' => NULL,
            ],
            [
                'name' => 'Tedi Mulyadi',
                'phone' => '-',
                'email' => 'tedi@reftech.id',
                'password' => Hash::make('teknisi123'),
                'code' => 'TMU',
                'area' => 'Bandung',
                'role' => 'Technician',
                'image' => 'asset/profile/profile.jpg',
                'sign' => NULL,
            ],
            [
                'name' => 'Candra Wahyudi',
                'phone' => '-',
                'email' => 'candra@reftech.id',
                'password' => Hash::make('teknisi123'),
                'code' => 'CWA',
                'area' => 'Bandung',
                'role' => 'Technician',
                'image' => 'asset/profile/profile.jpg',
                'sign' => NULL,
            ],
            [
                'name' => 'Trimo Susandanu',
                'phone' => '-',
                'email' => 'danu@reftech.id',
                'password' => Hash::make('teknisi123'),
                'code' => 'TSU',
                'area' => 'Bandung',
                'role' => 'Technician',
                'image' => 'asset/profile/profile.jpg',
                'sign' => NULL,
            ],
            [
                'name' => 'Reza Hilmi Maulana',
                'phone' => '-',
                'email' => 'reza@reftech.id',
                'password' => Hash::make('teknisi123'),
                'code' => 'RHM',
                'area' => 'Bandung',
                'role' => 'Technician',
                'image' => 'asset/profile/profile.jpg',
                'sign' => NULL,
            ],
            [
                'name' => 'Faris Abu Hafidz',
                'phone' => '-',
                'email' => 'faris@reftech.id',
                'password' => Hash::make('teknisi123'),
                'code' => 'FAH',
                'area' => 'Bandung',
                'role' => 'Technician',
                'image' => 'asset/profile/profile.jpg',
                'sign' => NULL,
            ],
        ];
        User::insert($user);
    }
}
