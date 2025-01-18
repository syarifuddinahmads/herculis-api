<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RequestKuotaDetailTable extends Migration
{
    public function up()
    {
        $field = [
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'requestKuota_id' => [
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
        $this->forge->createTable('request_kuota_detail');
    }

    public function down()
    {
        $this->forge->dropTable('request_kuota_detail');
    }
}
