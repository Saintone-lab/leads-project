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
                'phone' => '081267987456',
                'email' => 'sales@reftech.id',
                'password' => Hash::make('sales'),
                'code' => 'YH',
                'area' => 'Bandung',
                'role' => 'sales',
                'image' => 'profile.jpg',
            ],
            [
                'name' => 'Regita Dwi Melinda',
                'phone' => '081266823456',
                'email' => 'regita@reftech.id',
                'password' => Hash::make('sales'),
                'code' => 'RM',
                'area' => 'Bandung',
                'role' => 'sales',
                'image' => 'profile.jpg',
            ],
            [
                'name' => 'Rifan Fahli',
                'phone' => '081294857656',
                'email' => 'rifan@reftech.id',
                'password' => Hash::make('sales'),
                'code' => 'RF',
                'area' => 'Bandung',
                'role' => 'sales',
                'image' => 'profile.jpg',
            ],
            [
                'name' => 'Yolan Yolanda',
                'phone' => '081294857656',
                'email' => 'support@reftech.id',
                'password' => Hash::make('sales'),
                'code' => 'CRM',
                'area' => 'Bandung',
                'role' => 'sales',
                'image' => 'profile.jpg',
            ],
        ];
        User::insert($user);
    }
}
