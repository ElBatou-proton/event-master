<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEventTasksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'event_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'provider_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'due_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'priority' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'medium',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'todo',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('provider_id', 'providers', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('event_tasks', true);
    }

    public function down()
    {
        $this->forge->dropTable('event_tasks', true);
    }
}
