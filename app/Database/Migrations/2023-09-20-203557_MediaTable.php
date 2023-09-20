<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MediaTable extends Migration
{
    public function up()
    {
        $field = [
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'type_media' => [
                'type'           => 'VARCHAR',
                'constraint'     => 150,
            ],
            'url' => [
                'type'           => 'TEXT',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ];
        $this->forge->addField($field);
        $this->forge->addKey('id', true);
        $this->forge->createTable('media');
    }

    public function down()
    {
        $this->forge->dropTable('media');
    }
}
