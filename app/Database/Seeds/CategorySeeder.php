<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'category_name' => 'Fiksi',
                'description' => 
                        'Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia accusantium quod corporis sunt repudiandae nobis, quas eos necessitatibus atque hic temporibus nam, at repellat similique commodi cumque iste reprehenderit dolorem!'
            ],
            [
                'category_name' => 'Novel',
                'description' => 
                        'Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia accusantium quod corporis sunt repudiandae nobis.'
            ],
            [
                'category_name' => 'Science',
                'description' => 
                        'Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia accusantium quod corporis sunt repudiandae nobis, quas eos necessitatibus atque hic temporibus nam, at repellat similique commodi cumque iste reprehenderit dolorem! Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia accusantium quod corporis sunt repudiandae nobis, quas eos necessitatibus atque hic temporibus nam, at repellat similique commodi cumque iste reprehenderit dolorem!'
            ],
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}
