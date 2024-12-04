<?php

namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(ClassSeeder::class);
        $this->call(BiodataSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(BookSeeder::class);
    }
}
