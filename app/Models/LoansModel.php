<?php

namespace App\Models;

use CodeIgniter\Model;

class LoansModel extends Model
{
    protected $table            = 'loans';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'book_id',
        'user_id',
        'pelayan_id',
        'loan_date',
        'return_date_expected',
        'return_date_actual',
        'status',
        'quantity',
        'notes'
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

    public function getAllLoans()
    {
        // Ambil semua data peminjaman
        return $this
            ->select('loans.*, books.book_name, books.author, users.id, biodatausers.fullname')
            ->join('books', 'books.id = loans.book_id')
            ->join('users', 'users.id = loans.user_id')
            ->join('biodatausers', 'biodatausers.id = users.id')
            ->findAll();
    }
    /**
     * Menghitung jumlah peminjaman yang dilakukan dalam 7 hari terakhir
     * 
     * Fungsi ini akan mengembalikan jumlah peminjaman yang dilakukan dalam 7
     * hari terakhir
     * 
     * @return int jumlah peminjaman yang dilakukan dalam 7 hari terakhir
     */
    public function countNewLoans()
    {
        // Untuk menghitung jumlah record, gunakan countAllResults()
        return $this->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
            ->countAllResults();
    }
    /**
     * Mengambil data peminjaman berdasarkan id user yang dikirimkan
     * 
     * Fungsi ini akan mengembalikan data peminjaman yang memiliki id user yang
     * sama dengan parameter
     * 
     * @param int $id_user id user yang akan diambil
     * 
     * @return array data peminjaman yang tersedia
     */
    public function getLoanByIdUser($id_user)
    {
        // Ambil data peminjaman yang memiliki id user yang sama
        // dengan parameter
        return $this
            ->select('loans.*, books.book_name, categories.category_name')
            ->join('books', 'books.id = loans.book_id')
            ->join('categories', 'categories.id = books.category_id')
            ->where('loans.user_id', $id_user)
            ->findAll();
    }

    /**
     * Mengambil data peminjaman yang tersedia untuk user yang dikirimkan
     * 
     * Fungsi ini akan mengembalikan data peminjaman yang memiliki id user yang
     * sama dengan parameter dan memiliki status 'Borrowed' atau 'Overdue'
     * 
     * @param int $id_user id user yang akan diambil
     * 
     * @return array data peminjaman yang tersedia
     */
    public function getAvailableLoans($id_user)
    {
        return $this
            ->select('loans.*, books.book_name, categories.category_name')
            ->join('books', 'books.id = loans.book_id')
            ->join('categories', 'categories.id = books.category_id')
            ->where('loans.user_id', $id_user)
            ->whereIn('loans.status', ['Borrowed', 'Overdue'])
            ->findAll();
    }

    /**
     * Menghapus data peminjaman berdasarkan id user yang dikirimkan
     * 
     * Fungsi ini akan menghapus data peminjaman yang memiliki id user yang sama
     * dengan parameter
     * 
     * @param int $id_user id user yang akan dihapus
     * 
     * @return bool hasil penghapusan data
     */
    public function deleteLoan($id_user)
    {
        return $this->delete(['user_id' => $id_user]);
    }

    public function getCountLoanByIdBook($id_book)
    {
        return $this
            ->where('book_id', $id_book)
            ->where('status', 'Borrowed')
            ->countAllResults();
    }
}
