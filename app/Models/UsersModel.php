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


    public function getDataByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
    public function countUserByRole($role)
    {
        return $this->where('role', $role)->countAllResults();
    }
    public function getDataByEmail($email)
    {
        return $this->where('email', $email)->first();
    }


    /**
     * Mengambil data user berdasarkan role yang diinginkan
     * 
     * Fungsi ini akan mengembalikan data user yang memiliki role yang sama dengan parameter
     * Fungsi ini akan mengembalikan data dari table users dan biodatausers
     * 
     * @param string $role role yang diinginkan
     * @return array data user yang memiliki role yang sama dengan parameter
     */
    public function getAllRoleByRole($role)
    {
        return $this
            ->select('users.email, users.username, users.role, biodatausers.fullname, biodatausers.identification, 
                    biodatausers.address, biodatausers.phone, biodatausers.created_at')
            ->join('biodatausers', 'biodatausers.user_id = users.id', 'left')
            ->where('users.role', $role)
            ->findAll();
    }

    /**
     * Mengambil data user berdasarkan id yang diinginkan
     * 
     * Fungsi ini akan mengembalikan data user yang memiliki id yang sama dengan parameter
     * Fungsi ini akan mengembalikan data dari table users
     * 
     * @param int $id id user yang diinginkan
     * @return array data user yang memiliki id yang sama dengan parameter
     */
    public function getDataUserById($id)
    {
        return $this
            ->select('users.id, users.email, users.role, users.username')
            ->where('users.id', $id)
            ->first();
    }

    /**
     * Retrieve detailed user information by user ID
     * 
     * This function fetches detailed information about a user, including
     * data from the users, biodatausers, and classes tables.
     * 
     * @param int $id The ID of the user to retrieve
     * @return array|null The user's detailed information or null if not found
     */
    public function getDetailUserById($id)
    {
        return $this
            ->select('users.*, biodatausers.*, classes.class_name as class_name')
            ->join('biodatausers', 'biodatausers.user_id = users.id', 'left')
            ->join('classes', 'classes.id = biodatausers.class_id', 'left')
            ->where('users.id', $id)
            ->first();
    }

    /**
     * Menambahkan data user baru
     * 
     * Fungsi ini akan menambahkan data user baru berdasarkan data yang dikirimkan
     * Fungsi ini akan mengembalikan hasil insert data
     * 
     * @param array $data data user yang akan ditambahkan
     * @return bool hasil insert data
     */
    public function insertData($data)
    {
        return $this->insert($data);
    }

    /**
     * Memperbarui data user berdasarkan id yang dikirimkan
     * 
     * Fungsi ini akan memperbarui data user yang memiliki id yang sama dengan parameter
     * Fungsi ini akan mengembalikan hasil update data
     * 
     * @param int $id_user id user yang akan diperbarui
     * @param array $data_update data yang akan diperbarui
     * 
     * @return bool hasil update data
     */
    public function updateUser($id_user, $data_update)
    {
        return $this->update($id_user, $data_update);
    }

    /**
     * Menghapus data user berdasarkan id yang dikirimkan
     * 
     * Fungsi ini akan menghapus data user yang memiliki id yang sama dengan parameter
     * Fungsi ini akan mengembalikan hasil hapus data
     * 
     * @param int $id_user id user yang akan dihapus
     * 
     * @return bool hasil hapus data
     */
    public function deleteUser($id_user)
    {
        return $this->delete($id_user);
    }
}
