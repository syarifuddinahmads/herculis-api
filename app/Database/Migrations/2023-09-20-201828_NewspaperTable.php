<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NewspaperTable extends Migration
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
            'publisher_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'price' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ];
        $this->forge->addField($field);
        $this->forge->addKey('id', true);
        $this->forge->createTable('newspaper');
    }

    public function down()
    {
        $this->forge->dropTable('newspaper');
    }
}
