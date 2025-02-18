<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassesModel extends Model
{
    protected $table            = 'classes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'class_name'];

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
     * Mengambil semua data kelas yang tersedia di database.
     * Fungsi ini akan mengembalikan semua data kelas yang ada di database.
     * @return array data kelas yang tersedia
     */
    public function getAllClasses()
    {
        return $this->findAll();
    }

    public function getDetailClassById($id)
    {
        return $this->where('id', $id)->first();
    }

    /**
     * Memeriksa apakah nama kelas yang dikirimkan sudah ada di database
     * 
     * Fungsi ini akan mengembalikan true jika nama kelas yang dikirimkan sudah ada di database,
     * dan false jika tidak.
     * 
     * @param string $class_name nama kelas yang akan diperiksa
     * 
     * @return bool true jika nama kelas yang dikirimkan sudah ada di database, false jika tidak
     */
    public function checkName($class_name)
    {
        $result = $this->where('class_name', $class_name)->first();
        return $result ? true : false;
    }
}
