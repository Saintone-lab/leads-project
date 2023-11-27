<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activities;

class ActivitiesTableSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $activities = [
            [
                'id_client' => '1',
                'name' => 'Daily Call',
                'status' => 'Responded',
                'date'        => date('Y-m-d h:i:s'),
                'action' => 'Number Office'
            ],
            [
                'id_client' => '1',
                'name' => 'Follow Up',
                'status' => 'Not Respon',
                'date'        => date('Y-m-d h:i:s'),
                'action' => 'WhatsApp'
            ],
            [
                'id_client' => '2',
                'name' => 'Daily Call',
                'status' => 'Not Respon',
                'date'        => date('Y-m-d h:i:s'),
                'action' => 'Number Office'
            ],
            [
                'id_client' => '3',
                'name' => 'Daily Call',
                'status' => 'Responded',
                'date'        => date('Y-m-d h:i:s'),
                'action' => 'Number Office'
            ],
        ];
        Activities::insert($activities);
    }
}
