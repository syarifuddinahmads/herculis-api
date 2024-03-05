<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NewspaperTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Jawa Pos Indonesia',
                'publisher_id' => 1,
                'price' => '25000'
            ],
            [
                'name' => 'Detik',
                'publisher_id' => 2,
                'price' => '25000'
            ],
            [
                'name' => 'Koran Sindo',
                'publisher_id' => 1,
                'price' => '25000'
            ]
        ];

        foreach ($data as $i) {
            $this->db->table('newspaper')->insert($i);
        }
    }
}
