<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductTable extends Migration
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
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'content' => [
                'type' => 'LONGTEXT',
                'null' => true
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'brand_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'null' => true
            ],
            'sku' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'price' => [
                'type' => 'FLOAT',
            ],
            'type_discount' => [
                'type' => 'TINYINT',
                'constraint' => '1',
                'null' => true
            ],
            'discount' => [
                'type' => 'FLOAT',
                'null' => true
            ],
            'price_result' => [
                'type' => 'FLOAT',
            ],
            'quantity' => [
                'type' => 'SMALLINT',
                'constraint' => '6',
                'default' => '0',
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => '1',
            ],
            'featured' => [
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => '0',
            ],
            'start_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'end_at' => [
                'type' => 'DATETIME',
                'null' => true
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
            ],
            'meta_title' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'meta_keyword' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'meta_description' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('product');
    }

    public function down()
    {
        $this->forge->dropTable('product');
    }
}
