<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel;

class Users extends UserModel
{

    protected $allowedFields = [
        'username',
        'full_name',
        'phone',
        'gender',
        'job',
        'birthdate',
        'avatar',
        'address',
        'status',
        'status_message',
        'active',
        'last_active',
        'deleted_at',
    ];
}
