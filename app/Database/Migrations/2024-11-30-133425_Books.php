<?php

namespace App\Database\Migrations;

use CodeIgniter\I18n\Time;
use CodeIgniter\Database\Migration;

class Books extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'category_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'constraint' => 5,
            ],
            'book_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'isbn' => [
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => 255,
                'null' => true,
            ],
            'author' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'publisher' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'year_published' => [
                'type' => 'INT',
                'constraint' => 4,
                'null' => true,
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'total_books' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
            ],
            'total_copies' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
            ],
            'cover_img' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => Time::now('Asia/Jakarta', 'id_ID'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ]
        ]);

        $this->forge->addPrimaryKey('id', 'books');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('books');
    }

    public function down()
    {
        $this->forge->dropTable('books');
    }
}
