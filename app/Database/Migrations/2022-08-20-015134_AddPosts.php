<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPosts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'slug' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'excerpt' => [
                'type'           => 'TEXT',
                'null'           => true,
                'default'        => null,
            ],
            'content' => [
                'type'            => 'LONGTEXT',
                'null'            => true,
                'default'         => null,
            ],
            'category_id' => [
                'type'            => 'INT',
                'unsigned'        => true,
            ],
            'cover' => [
                'type'            => 'VARCHAR',
                'constraint'      => '255',
            ],
            'date' => [
                'type'            => 'DATETIME',
                'null'            => true,
                'default'         => null,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('posts');
    }

    public function down()
    {
        $this->forge->dropForeignKey('posts', 'posts_category_id_foreign');
        $this->forge->dropTable('posts');
    }
}
