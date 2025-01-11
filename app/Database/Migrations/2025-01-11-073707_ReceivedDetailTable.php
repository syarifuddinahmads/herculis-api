<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ReceivedDetailTable extends Migration
{
    public function up()
    {
        $field = [
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'received_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'transaction_detail_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'quantity' => [
                'type'           => 'INT',
                'default'        => 0
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ];
        $this->forge->addField($field);
        $this->forge->addKey('id', true);
        $this->forge->createTable('received_detail');
    }

    public function down()
    {
        $this->forge->dropTable('received_detail');
    }
}
