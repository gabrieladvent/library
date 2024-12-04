<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'email' => 'admin_satu@example.com',
                'username' => 'adminsatu',
                'password' => password_hash('admin_satu', PASSWORD_DEFAULT),
                'role' => 'Admin'
            ],
            [
                'email' => null,
                'username' => 'admindua',
                'password' => password_hash('admin_dua', PASSWORD_DEFAULT),
                'role' => 'Admin'
            ],
            [
                'email' => 'user_satu@example.com',
                'username' => 'usersatu',
                'password' => password_hash('user_satu', PASSWORD_DEFAULT),
                'role' => 'User'
            ],
            [
                'email' => 'user_dua@example.com',
                'username' => 'userdua',
                'password' => password_hash('user_dua', PASSWORD_DEFAULT),
                'role' => 'User'
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
