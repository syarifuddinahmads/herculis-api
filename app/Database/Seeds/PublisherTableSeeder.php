<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PublisherTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Jawa Pos Indonesia',
                'address' => 'Jakarta Selatan, Indonesia',
                'no_telp' => '08123456789'
            ],
            [
                'name' => 'Detik',
                'address' => 'Jakarta Selatan, Indonesia',
                'no_telp' => '08123456789'
            ],
            [
                'name' => 'Koran Sindo',
                'address' => 'Jakarta Selatan, Indonesia',
                'no_telp' => '08123456789'
            ]
        ];

        foreach ($data as $i) {
            $this->db->table('publisher')->insert($i);
        }
    }
}
