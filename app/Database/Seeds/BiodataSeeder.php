<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BiodataSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id'        => 1,
                'fullname'       => 'John Doe',
                'identification' => '1234567890123456',
                'gender'         => 'Laki-Laki',
                'religion'       => 'Katolik',
                'place_birth'    => 'New York',
                'date_birth'     => '1990-05-15',
                'phone'          => '081234567890',
                'address'        => '123 Main St, New York, USA',
                'class_id'       => null,
            ],
            [
                'user_id'        => 2,
                'fullname'       => 'Jane Smith',
                'identification' => '9876543210987654',
                'gender'         => 'Perempuan',
                'religion'       => 'Islam',
                'place_birth'    => 'Los Angeles',
                'date_birth'     => '1995-11-20',
                'phone'          => '082345678901',
                'address'        => '456 Elm St, Los Angeles, USA',
                'class_id'       => null,
            ],
            [
                'user_id'        => 3,
                'fullname'       => 'Michael Johnson',
                'identification' => '1122334455667788',
                'gender'         => 'Laki-Laki',
                'religion'       => 'Hindu',
                'place_birth'    => 'Chicago',
                'date_birth'     => '1988-03-10',
                'phone'          => '083456789012',
                'address'        => '789 Oak St, Chicago, USA',
                'class_id'       => 2,
            ],
            [
                'user_id'        => 4,
                'fullname'       => 'Emily Davis',
                'identification' => '3344556677889900',
                'gender'         => 'Perempuan',
                'religion'       => 'Budha',
                'place_birth'    => 'San Francisco',
                'date_birth'     => '1992-07-25',
                'phone'          => '084567890123',
                'address'        => '321 Pine St, San Francisco, USA',
                'class_id'       => 3,
            ],
        ];

        $this->db->table('biodatausers')->insertBatch($data);
    }
}
