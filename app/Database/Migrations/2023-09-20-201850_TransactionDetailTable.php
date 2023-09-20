<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TransactionDetailTable extends Migration
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
            'transaction_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'newspaper_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'price' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'quantity' => [
                'type'           => 'INT',
                'constraint'     => 11,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ];
        $this->forge->addField($field);
        $this->forge->addKey('id', true);
        $this->forge->createTable('transaction_detail');
    }

    public function down()
    {
        $this->forge->dropTable('transaction_detail');
    }
}
