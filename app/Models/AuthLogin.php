<?php

namespace App\Models;

use App\Entities\AuthLoginEntity;
use CodeIgniter\Model;

class AuthLogin extends Model
{
    protected $table = 'auth_logins';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = AuthLoginEntity::class;

    public function getDeviceUser($user_id): array
    {
        return $this->select('user_agent, date')
            ->where('user_id', $user_id)
            ->where('success', AUTH_LOGIN_SUCCESS)
            ->orderBy('date', 'desc')
            ->findAll();
    }
}
