<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'address' => '123 Main St, Anytown, USA',
                'no_telp' => '123-456-7890',
                'nik_image' => '-',
                'profile_image' => '-',
                'user_type_id' => 1,
            ],
            [
                'name' => 'Staff Pembelian',
                'email' => 'staff-buy@mail.com',
                'password' => password_hash('password456', PASSWORD_DEFAULT),
                'address' => '456 Elm St, Anytown, USA',
                'no_telp' => '987-654-3210',
                'nik_image' => '-',
                'profile_image' => '-',
                'user_type_id' => 2,
            ],
            [
                'name' => 'Staff Penjualan',
                'email' => 'staff-sales@mail.com',
                'password' => password_hash('password456', PASSWORD_DEFAULT),
                'address' => '456 Elm St, Anytown, USA',
                'no_telp' => '987-654-3210',
                'nik_image' => '-',
                'profile_image' => '-',
                'user_type_id' => 3,
            ],
            [
                'name' => 'Asongan',
                'email' => 'asongan@mail.com',
                'password' => password_hash('password456', PASSWORD_DEFAULT),
                'address' => '456 Elm St, Anytown, USA',
                'no_telp' => '987-654-3210',
                'nik_image' => '-',
                'profile_image' => '-',
                'user_type_id' => 4,
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@mail.com',
                'password' => password_hash('password456', PASSWORD_DEFAULT),
                'address' => '456 Elm St, Anytown, USA',
                'no_telp' => '987-654-3210',
                'nik_image' => '-',
                'profile_image' => '-',
                'user_type_id' => 4,
            ],
            // Tambahkan data lainnya sesuai kebutuhan
        ];

        foreach ($data as $i) {
            $this->db->table('users')->insert($i);
        }
    }
}
