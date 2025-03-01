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
        return $this
            ->select('
                    loans.id AS loan_id,
                    loans.book_id, 
                    loans.user_id, 
                    loans.loan_date, 
                    loans.return_date_expected,
                    loans.quantity,
                    loans.status, 
                    books.book_name, 
                    books.author, 
                    users.id AS user_id, 
                    biodatausers.fullname
                ')
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
        return $this->where('loan_date >=', date('Y-m-d H:i:s', strtotime('-7 days')))
            ->countAllResults();
    }


    public function countDailyLoansByStatus()
    {
        return $this->select("DAYNAME(loan_date) as loan_day, status, COUNT(*) as total")
            ->where('loan_date >=', date('Y-m-d H:i:s', strtotime('-7 days')))
            ->groupBy(['loan_day', 'status'])
            ->orderBy('MIN(loan_date)', 'ASC')
            ->findAll();
    }

    public function countLoansByClass()
    {
        return $this->select("classes.class_name, COUNT(*) as total")
            ->join('biodatausers', 'biodatausers.user_id = loans.user_id') // Join dengan biodatausers
            ->join('classes', 'classes.id = biodatausers.class_id') // Join dengan class
            ->groupBy('classes.class_name')
            ->orderBy('total', 'DESC') // Urutkan berdasarkan jumlah peminjaman terbanyak
            ->findAll();
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
     * Mengambil data peminjaman yang tersedia untuk user yang mkan
     * dikiri
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

    public function getDetailLoanByIdLoan($id_loan)
    {
        return $this
            ->select('
            loans.id AS loan_id, 
            loans.book_id, 
            loans.user_id, 
            loans.loan_date, 
            loans.return_date_expected, 
            loans.return_date_actual, 
            loans.quantity, 
            loans.status, 
            loans.notes, 
            books.book_name, 
            books.author,
            books.isbn, 
            users.id AS user_id, 
            biodatausers.fullname,
            biodatausers.identification,
            classes.class_name
        ')
            ->join('books', 'books.id = loans.book_id')
            ->join('users', 'users.id = loans.user_id')
            ->join('biodatausers', 'biodatausers.id = users.id')
            ->join('classes', 'classes.id = biodatausers.class_id')
            ->where('loans.id', $id_loan)
            ->first();
    }

    public function getLoansByFilter($loansDate = null, $returnDate = null, $status = null)
    {
        $this->select('
            loans.id AS loan_id, 
            loans.book_id, 
            loans.user_id, 
            loans.loan_date, 
            loans.return_date_expected, 
            loans.return_date_actual, 
            loans.quantity, 
            loans.status, 
            loans.notes, 
            books.book_name, 
            books.author,
            books.isbn, 
            users.id AS user_id, 
            biodatausers.fullname,
            biodatausers.identification,
            classes.class_name
        ')
            ->join('books', 'books.id = loans.book_id')
            ->join('users', 'users.id = loans.user_id')
            ->join('biodatausers', 'biodatausers.id = users.id')
            ->join('classes', 'classes.id = biodatausers.class_id');

        if (!empty($loansDate)) {
            $this->where("DATE(loans.loan_date)", $loansDate);
        }

        if (!empty($returnDate)) {
            $this->where("DATE(loans.return_date)", $returnDate);
        }

        // Filter berdasarkan status jika ada
        if (!empty($status)) {
            $this->where("loans.status", $status);
        }

        return $this->orderBy("loans.loan_date", "DESC")
            ->findAll();
    }
}
