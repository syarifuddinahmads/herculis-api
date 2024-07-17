<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ReturTable extends Migration
{
    public function up()
    {
        $field = [
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'retur_code' => [
                'type'           => 'VARCHAR', // ex : 20230921A2GKLM57
                'constraint'     => 16,
            ],
            'transaction_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'staff_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'publisher_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'total_price' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'date_retur datetime default current_timestamp',
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ];
        $this->forge->addField($field);
        $this->forge->addKey('id', true);
        $this->forge->createTable('retur');
    }

    public function down()
    {
        $this->forge->dropTable('retur');
    }
}
