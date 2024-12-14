<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'email',
        'username',
        'password',
        'role'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getDataByEmail($email) {
        return $this->where('email', $email)->first();
    }

    public function getDataByUsername($username) {
        return $this->where('username', $username)->first();
    }

    public function countRoleUser()
    {
        return $this->where('role', 'User')->countAllResults();
    }
    
    public function countRoleAdmin()
    {
        return $this->where('role', 'Admin')->countAllResults();
    }

    public function getDataUserById($id)
    {
        return $this
            ->select('users.id, users.email, users.role, users.username')
            -> where('users.id', $id)
            ->first();
    }
}
