<?php

namespace App\Database\Seeds;

use App\Models\Users;
use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class UserAdmin extends Seeder
{
    public function run()
    {
        helper(['auth', 'setting']);
        $users = new Users();
        $user = new User([
            'username' => 'longdang0412',
            'email' => 'longdang0412@gmail.com',
            'password' => getenv('PASSWORD_ADMIN'),
        ]);
        $users->save($user);

        $user = $users->findById($users->getInsertID());
        setting('AuthGroups.defaultGroup', 'superadmin');

        $users->addToDefaultGroup($user);
    }
}
