<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

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
                'name' => 'Mr Yusuf',
                'phone' => '081267987456',
                'email' => 'yusuf@gmail.com',
                'password' => 'yusufreftech123',
                'area' => 'bandung',
                'role' => 'sales',
            ],
            [
                'name' => 'Ms Regita',
                'phone' => '081266823456',
                'email' => 'regita@gmail.com',
                'password' => 'regitaairn123',
                'area' => 'bandung',
                'role' => 'sales',
            ],
            [
                'name' => 'Mr Ari',
                'phone' => '081294857656',
                'email' => 'ari@gmail.com',
                'password' => 'aritech123',
                'area' => 'bandung',
                'role' => 'technician',
            ],
        ];
        User::insert($user);
    }
}
