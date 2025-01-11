<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ReceivedTable extends Migration
{
    public function up()
    {
        $field = [
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'transaction_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'date_received datetime default current_timestamp',
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ];
        $this->forge->addField($field);
        $this->forge->addKey('id', true);
        $this->forge->createTable('received');
    }

    public function down()
    {
        $this->forge->dropTable('received');
    }
}
