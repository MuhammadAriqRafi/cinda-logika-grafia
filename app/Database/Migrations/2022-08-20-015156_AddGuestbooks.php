<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGuestbooks extends Migration
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
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['read', 'unread'],
                'default' => 'unread',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('guestbooks');
    }

    public function down()
    {
        $this->forge->dropTable('guestbooks');
    }
}
