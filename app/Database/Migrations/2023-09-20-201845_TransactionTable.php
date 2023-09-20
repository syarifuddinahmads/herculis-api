<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TransactionTable extends Migration
{
    public function up()
    {
        $field = [
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'transaction_code' => [
                'type'           => 'VARCHAR', // ex : 20230921A2GKLM57
                'constraint'     => 16,
            ],
            'user_id' => [
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
            'payment_status' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
            ],
            'payment_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null'           => true,
            ],
            'date_transaction' => [
                'type'           => 'DATETIME',
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
        $this->forge->createTable('transaction');
    }

    public function down()
    {
        $this->forge->dropTable('transaction');
    }
}
