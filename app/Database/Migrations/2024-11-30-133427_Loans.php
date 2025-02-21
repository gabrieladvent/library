<?php

namespace App\Database\Migrations;

use CodeIgniter\I18n\Time;
use CodeIgniter\Database\Migration;

class Loans extends Migration
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
            'book_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'constraint' => 5,
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'constraint' => 5,
            ],
            'loan_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'return_date_expected' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'return_date_actual' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Dipinjam', 'Diperpanjang', 'Dikembalikan', 'Terlambat'],
                'default' => 'Dipinjam',
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
            ],
            'notes' => [
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

        $this->forge->addPrimaryKey('id', 'loans');
        $this->forge->addForeignKey('book_id', 'books', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('loans');
    }

    public function down()
    {
        $this->forge->dropTable('loans');
    }
}
