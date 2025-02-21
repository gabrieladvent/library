<?php

namespace App\Models;

use CodeIgniter\Model;

class BiodataUsersModel extends Model
{
    protected $table            = 'biodatausers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'user_id',
        'fullname',
        'identification',
        'gender',
        'religion',
        'place_birth',
        'date_birth',
        'phone',
        'address',
        'class_id'
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

    /**
     * Menambahkan data ke table biodatausers
     *
     * @param array $data data yang akan ditambahkan
     *
     * @return bool hasil penambahan data
     */
    public function insertData(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Memperbarui data pada table biodatausers
     *
     * Fungsi ini akan memperbarui data berdasarkan id user yang dikirimkan
     * Fungsi ini akan mengembalikan hasil update data
     *
     * @param int $id_user id user yang akan diperbarui
     * @param array $data_update data yang akan diperbarui
     *
     * @return bool hasil update data
     */
    public function updateBiodataUser(int $id_user, array $data_update): bool
    {
        $id_biodata = $this->getBiodataUserById($id_user);
        return $this->update($id_biodata['id'], $data_update);
    }

    /**
     * Menghapus data biodata user berdasarkan id user yang dikirimkan
     *
     * Fungsi ini akan menghapus data biodata user yang memiliki id user yang sama dengan parameter
     * Fungsi ini akan mengembalikan hasil penghapusan data
     *
     * @param int $id_user id user yang akan dihapus
     *
     * @return bool hasil penghapusan data
     */
    public function deleteBiodata(int $id_user): bool
    {
        $id_biodata = $this->getBiodataUserById($id_user);
        return $this->delete($id_biodata['id']);
    }

    /**
     * Mengambil data biodata user berdasarkan id user yang dikirimkan
     *
     * Fungsi ini akan mengembalikan data biodata user yang memiliki id user yang sama dengan parameter
     * Fungsi ini akan mengembalikan data dari table biodatausers
     *
     * @param int $id_user id user yang akan diambil
     * @return array data biodata user yang memiliki id user yang sama dengan parameter
     */
    public function getBiodataUserById($id_user)
    {
        return $this->where('user_id', $id_user)->first();
    }

    public function getDataByClassId($class_id)
    {
        return $this->where('class_id', $class_id)->countAllResults();
    }

    public function getClassUser($id_user)
    {
        return $this->select('biodatausers.class_id, classes.class_name')
            ->join('classes', 'classes.id = biodatausers.class_id', 'left')
            ->where('biodatausers.id', $id_user) 
            ->first();
    }
}
