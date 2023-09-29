<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'User 1',
                'password' => '123456',
                'email' => '08123456789'
            ],
            [
                'name' => 'User 2',
                'password' => '123456',
                'email' => '08123456789'
            ],
            [
                'name' => 'User 3',
                'password' => '123456',
                'email' => '08123456789'
            ]
        ];

        foreach ($data as $i) {
            $this->db->table('publisher')->insert($i);
        }
    }
}
