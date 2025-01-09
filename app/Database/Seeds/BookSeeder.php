<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [   
                'category_id' => 1,
                'book_name' => 'Laskar Pelangi',
                'isbn' => '978-3-16-148410-0',
                'author' => json_encode(['Andrea Hirata']),
                'publisher' => 'Gramedia',
                'year_published' => '2017',
                'description' => 
                        'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatum harum voluptates odio quisquam, hic nam neque ab itaque non nostrum voluptate, dicta doloremque corporis voluptatem provident officia? Earum, quasi libero.',
                'total_books' => 20,
                'total_copies' => 20,
                'cover_img' => null,
            ],
            [
                'category_id' => 2,
                'book_name' => 'Buku Paket Bahasa Ingris',
                'isbn' => '978-0-393-04002-9',
                'author' => json_encode(['Andrea Hirata', 'J. K. Rowling']),
                'publisher' => 'Dapus Media',
                'year_published' => '2005',
                'description' => 
                        'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatum harum voluptates odio quisquam, hic nam neque ab itaque non nostrum voluptate, dicta doloremque corporis voluptatem provident officia? Earum, quasi libero. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatum harum voluptates odio quisquam, hic nam neque ab itaque non nostrum voluptate, dicta doloremque corporis voluptatem provident officia? Earum, quasi libero.',
                'total_books' => 40,
                'total_copies' => 20,
                'cover_img' => null,
            ],
            [
                'category_id' => 3,
                'book_name' => 'Dektektifos',
                'isbn' => '978-1-56619-909-4',
                'author' => json_encode(['J. K. Rowling', 'J. R. R. Tolkien']),
                'publisher' => 'Gramedias',
                'year_published' => '2010',
                'description' => 
                        'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatum harum voluptates odio quisquam, hic nam neque ab itaque non nostrum voluptate, dicta doloremque corporis voluptatem provident officia? Earum, quasi libero. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatum harum voluptates odio quisquam, hic nam neque ab itaque non nostrum voluptate, dicta doloremque corporis voluptatem provident officia? Earum, quasi libero. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatum harum voluptates odio quisquam, hic nam neque ab itaque non nostrum voluptate, dicta doloremque corporis voluptatem provident officia? Earum, quasi libero.',
                'total_books' => 10,
                'total_copies' => 10,
                'cover_img' => null,
            ],
        ];

        $this->db->table('books')->insertBatch($data);
    }
}