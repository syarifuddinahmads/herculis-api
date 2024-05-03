<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableTransactionDetailAddColoumnReturQuantity extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transaction_detail', [
            'retur_quantity' => [
                'type' => 'integer',
                'after' => 'quantity'
            ],                       
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transaction_detail', 'retur_quantity');
    }
}
