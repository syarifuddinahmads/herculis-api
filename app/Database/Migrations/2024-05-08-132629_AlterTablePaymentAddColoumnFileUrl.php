<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTablePaymentAddColoumnFileUrl extends Migration
{
    public function up()
    {
        {
           
            $this->forge->addColumn('payment', [
                'file_url' => [
                    'type' => 'varchar',
                    'constraint' => 225,
                    'after' => 'status_payment'
                ],
                'note' => [
                    'type' => 'text',
                    'after' => 'file_url'
                ]             
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('payment', 'file_url');
        $this->forge->dropColumn('payment', 'note');
    }
}
