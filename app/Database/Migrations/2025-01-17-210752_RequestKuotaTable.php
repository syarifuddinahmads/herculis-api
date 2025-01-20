<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RequestKuotaTable extends Migration
{
    public function up()
    {
        $field = [
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'total' => [
                'type'           => 'INT',
                'default'        => 0,
                'null'           => true,
            ],
            'note' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
            ],
            'status' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'default'        => 'pending',
            ],
            'status_payment' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'default'        => 'unpaid',
            ],
            'date_requestKuota datetime default current_timestamp',
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ];
        $this->forge->addField($field);
        $this->forge->addKey('id', true);
        $this->forge->createTable('request_kuota');
    }

    public function down()
    {
        $this->forge->dropTable('request_kuota');
    }
}
