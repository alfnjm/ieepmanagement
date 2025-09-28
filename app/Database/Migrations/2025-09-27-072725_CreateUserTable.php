<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR','constraint' => 100],
            'email'       => ['type' => 'VARCHAR','constraint' => 150,'unique' => true],
            'password'    => ['type' => 'VARCHAR','constraint' => 255],
            'class'       => ['type' => 'VARCHAR','constraint' => 50,'null' => true],
            'student_id'  => ['type' => 'VARCHAR','constraint' => 50,'null' => true],
            'phone'       => ['type' => 'VARCHAR','constraint' => 20,'null' => true],
            'ic_number'   => ['type' => 'VARCHAR','constraint' => 20,'null' => true],
            'created_at'  => ['type' => 'DATETIME','null' => true],
            'updated_at'  => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
