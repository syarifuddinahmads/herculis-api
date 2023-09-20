<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PaymentTable extends Migration
{
    public function up()
    {
        $field = [
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'type_payment' => [
                'type'           => 'VARCHAR',
                'constraint'     => 150,
            ],
            'payment_media_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'date_payment' => [
                'type'           => 'DATETIME',
            ],
            'status_payment' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ];
        $this->forge->addField($field);
        $this->forge->addKey('id', true);
        $this->forge->createTable('payment');
    }

    public function down()
    {
        $this->forge->dropTable('payment');
    }
}
