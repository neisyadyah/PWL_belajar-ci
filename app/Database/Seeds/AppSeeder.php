<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AppSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed Tabel user
        $userData = [
            [
                'username'   => 'admin_toko',
                'email'      => 'admin@belajarci.com',
                'password'   => password_hash('password123', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'user_pembeli',
                'email'      => 'pembeli@belajarci.com',
                'password'   => password_hash('pembeli123', PASSWORD_BCRYPT),
                'role'       => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $this->db->table('user')->insertBatch($userData);

        // 2. Seed Tabel product
        $productData = [
            [
                'nama'       => 'Sepatu Kursus CI4',
                'harga'      => 150000,
                'jumlah'     => 15,
                'foto'       => 'sepatu.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Hoodie Programmer Black',
                'harga'      => 225000,
                'jumlah'     => 30,
                'foto'       => 'hoodie.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $this->db->table('product')->insertBatch($productData);
    }
}