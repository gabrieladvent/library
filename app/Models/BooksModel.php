<?php

namespace App\Models;

use CodeIgniter\Model;

class BooksModel extends Model
{
    protected $table            = 'books';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'category_id',
        'book_name',
        'isbn',
        'author',
        'publisher',
        'year_published',
        'description',
        'total_books',
        'total_copies',
        'cover_img'
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
     * Mendapatkan jumlah data buku di database
     *
     * @return int
     */
    public function countAllBook()
    {
        return $this->countAll();
    }

    /**
     * Mendapatkan semua data buku di database
     *
     * Menggunakan query join untuk menghubungkan tabel books dengan tabel categories
     * untuk mendapatkan nama kategori untuk setiap buku.
     *
     * @return array
     */
    public function getAllBook()
    {
        return $this
            ->select('
                books.id, 
                books.book_name, 
                books.isbn, 
                books.author, 
                books.publisher,
                books.year_published,
                books.total_books, 
                books.category_id, 
                books.cover_img, 
                books.publisher,
                books.created_at,
                categories.category_name as category_name
                    ')
            ->join('categories', 'categories.id = books.category_id', 'left')
            ->findAll();
    }


    /**
     * Mendapatkan data buku berdasarkan id yang diinginkan
     * 
     * Fungsi ini akan mengembalikan data buku yang memiliki id yang sama dengan parameter
     * Fungsi ini akan mengembalikan data buku beserta nama kategori yang diambil dari tabel categories
     * 
     * @param int $id id buku yang diinginkan
     * @return array data buku yang memiliki id yang sama dengan parameter
     */
    public function getDataById($id)
    {
        return $this
            ->select('
                books.*,
                categories.category_name as category_name
                    ')
            ->join('categories', 'categories.id = books.category_id', 'left')
            ->where('books.id', $id)
            ->first();
    }


    /**
     * Mendapatkan jumlah data buku berdasarkan id kategori yang diinginkan
     * 
     * Fungsi ini akan mengembalikan jumlah data buku yang memiliki id kategori yang sama dengan parameter
     * 
     * @param int $category_id id kategori yang diinginkan
     * @return int jumlah data buku yang memiliki id kategori yang sama dengan parameter
     */
    public function getDataByCategoryId($category_id)
    {
        return $this->where('category_id', $category_id)->countAllResults();
    }
}
