<?php

namespace App\Database\Migrations;

use CodeIgniter\I18n\Time;
use CodeIgniter\Database\Migration;

class BiodataUser extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'constraint' => 5,
            ],
            'fullname' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'identification' => [
                'type' => 'INT',
                'constraint' => 20,
                'null' => true,
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['Laki-Laki', 'Perempuan'],
                'default' => null,
            ],
            'religion' => [
                'type' => 'ENUM',
                'constraint' => ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu'],
                'default' => null,
            ],
            'place_birth' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'date_birth' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'null' => true,
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'class_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'constraint' => 5,
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

        $this->forge->addPrimaryKey('id', 'users');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('biodatausers');
    }

    public function down()
    {
        $this->forge->dropTable('biodatausers');
    }
}
