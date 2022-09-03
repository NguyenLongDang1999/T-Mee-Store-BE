<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'after' => 'username'
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'after' => 'full_name'
            ],
            'gender' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'after' => 'phone'
            ],
            'job' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'gender'
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'job'
            ],
            'birthdate' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'address'
            ],
            'provider_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'birthdate'
            ],
            'provider_uid' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'provider_name'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
