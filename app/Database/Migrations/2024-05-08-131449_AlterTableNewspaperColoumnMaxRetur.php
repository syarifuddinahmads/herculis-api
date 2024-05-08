<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableNewspaperColoumnMaxRetur extends Migration
{
    public function up()
    {
        $this->forge->addColumn('newspaper', [
            'max_retur' => [
                'type' => 'integer',
                'after' => 'price'
            ],                       
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('newspaper', 'max_retur');
    }
}
