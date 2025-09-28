<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClass extends Migration
{
    public function up()
    {
       $fields = [
            'class' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'user',
                'after' => 'role'
            ],
        ];

        $this->forge->addColumn('user', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user', 'role');
    }
}
