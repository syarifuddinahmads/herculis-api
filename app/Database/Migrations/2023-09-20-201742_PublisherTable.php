<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PublisherTable extends Migration
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
            'address' => [
                'type'           => 'TEXT',
            ],
            'no_telp' => [
                'type'           => 'VARCHAR',
                'constraint'     => 25,
            ],
            'logo_media_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null' => true
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ];
        $this->forge->addField($field);
        $this->forge->addKey('id', true);
        $this->forge->createTable('publisher');
    }

    public function down()
    {
        $this->forge->dropTable('publisher');
    }
}
