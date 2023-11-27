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
                'name' => 'Ms Vita',
                'email' => 'vita@gmail.com',
                'password' => 'vitareftech123',
                'area' => 'bandung',
                'role' => 'sales',
            ],
            [
                'name' => 'Ms Regita',
                'email' => 'regita@gmail.com',
                'password' => 'regitaairn123',
                'area' => 'bandung',
                'role' => 'sales',
            ],
            [
                'name' => 'Mr Ari',
                'email' => 'ari@gmail.com',
                'password' => 'aritech123',
                'area' => 'bandung',
                'role' => 'technician',
            ],
        ];
        User::insert($user);
    }
}
