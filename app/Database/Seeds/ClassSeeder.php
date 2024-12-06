<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'class_name' => 'X-A',
            ],
            [
                'class_name' => 'XI-A',
            ],
            [
                'class_name' => 'X-B',
            ],
        ];

        $this->db->table('classes')->insertBatch($data);
    }
}
