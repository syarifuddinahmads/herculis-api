<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsersTable extends Migration
{
    public function up()
    {
        $field = [
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'email' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'password' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'address' => [
                'type'           => 'TEXT',
            ],
            'no_telp' => [
                'type'           => 'VARCHAR',
                'constraint'     => 25,
            ],
            'nik_media_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null'           => true,
            ],
            'profile_media_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null'           => true,
            ],
            'user_type_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
            ],
        ];
        $this->forge->addField($field);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['email', '']);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
