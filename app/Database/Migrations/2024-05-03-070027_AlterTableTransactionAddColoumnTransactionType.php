<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableTransactionAddColoumnTransactionType extends Migration
{
    public function up()
    {
        {
           
            $this->forge->addColumn('transaction', [
                'payment_type' => [
                    'type' => 'varchar',
                    'constraint' => 100,
                    'after' => 'payment_id'
                ],
                'is_retur' => [
                    'type' => 'boolean',
                    'after' => 'payment_type'
                ],                
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('transaction', 'payment_type');
        $this->forge->dropColumn('transaction', 'is_retur');
    }
}
