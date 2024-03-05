<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NewspaperPriceTable extends Migration
{
    private $table = 'newspaper_price';
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
            'newspaper_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'price' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'price_sale' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ];
        $this->forge->addField($field);
        $this->forge->addKey('id', true);
        $this->forge->createTable($this->table);
    }

    public function down()
    {
        $this->forge->dropTable($this->table);
    }
}
