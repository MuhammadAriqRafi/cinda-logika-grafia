<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdministrator extends Migration
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
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '60',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '60',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'created_at' => [
                'type' => 'BIGINT',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('administrators');
    }

    public function down()
    {
        $this->forge->dropTable('administrators');
    }
}
