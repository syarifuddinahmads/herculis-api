<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserTypeTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Admin',
            ],
            [
                'name' => 'Staff Pembelian',
            ],
            [
                'name' => 'Staff Penjualan',
            ],
            [
                'name' => 'Asongan',
            ],
            [
                'name' => 'Customer',
            ],
        ];

        foreach ($data as $i) {
            $this->db->table('user_type')->insert($i);
        }
    }
}
