<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClass extends Migration
{
    public function up()
    {
        // Check if the 'class' column already exists in the 'users' table
        if (!$this->db->fieldExists('class', 'users')) {
            $fields = [
                'class' => [
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'default' => 'user', 
                    // Placing it after a known column
                    'after' => 'password' 
                ],
            ];

            // Add the column only if it doesn't exist
            $this->forge->addColumn('users', $fields);
        }
    }

    public function down()
    {
        // Check if the 'class' column exists before attempting to drop it
        if ($this->db->fieldExists('class', 'users')) {
            $this->forge->dropColumn('users', 'class');
        }
    }
}
