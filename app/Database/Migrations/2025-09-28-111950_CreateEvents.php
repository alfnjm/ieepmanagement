<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEvents extends Migration
{
    public function up()
    {
        // Events table
        $this->forge->addField([
            'id'          => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'title'       => ['type' => 'VARCHAR','constraint' => 255],
            'description' => ['type' => 'TEXT','null' => true],
            'thumbnail'   => ['type' => 'VARCHAR','constraint' => 255,'null' => true],
            'date'        => ['type' => 'DATE'],
            'location'    => ['type' => 'VARCHAR','constraint' => 255],
            'created_at'  => ['type' => 'DATETIME','null' => true],
            'updated_at'  => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('events');

        // Registrations table
        $this->forge->addField([
            'id'         => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'user_id'    => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'event_id'   => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'created_at' => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('registrations');
    }

    public function down()
    {
        $this->forge->dropTable('registrations');
        $this->forge->dropTable('events');
    }
}
