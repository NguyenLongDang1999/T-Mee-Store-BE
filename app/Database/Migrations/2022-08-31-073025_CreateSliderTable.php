<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSliderTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'url' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => '1',
            ],
            'sort' => [
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => '0',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('slider');
    }

    public function down()
    {
        $this->forge->dropTable('slider');
    }
}
