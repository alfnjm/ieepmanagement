<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEventRegistrationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'event_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'certificate_ready' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('event_registrations');
    }

    public function down()
    {
        $this->forge->dropTable('event_registrations');
    }
}
