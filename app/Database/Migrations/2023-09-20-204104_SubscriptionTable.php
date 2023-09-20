<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SubscriptionTable extends Migration
{
    public function up()
    {
        $field = [
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'newspaper_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'subscription_status' => [
                'type'           => 'BOOLEAN',
            ],
            'date_subscription' => [
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
        $this->forge->createTable('subscription');
    }

    public function down()
    {
        $this->forge->dropTable('subscription');
    }
}
