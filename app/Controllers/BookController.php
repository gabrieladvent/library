<?php

namespace App\Controllers;

use App\Models\BooksModel;
use App\Models\LoansModel;
use App\Helpers\ResponHelper;
use App\Controllers\BaseController;

class BookController extends BaseController
{
    // judul buku (string) : judul_buku
    // pengarang (string) : Pengarang
    // penerbit (string) : Penerbit
    // Tahun Terbit(int) : tahun_terbit
    // kategory (string) : Kategory
    // jumlah buku (int) : jumlah_buku
    // sampul(img) : Sampul

    /* 
        dari parameter dan varaibel di atas 
        saya ingin kau buat fitur
        menampilkan data, tambah data ,edit data dan delete data
    */

    protected $book;
    protected $loan;
    protected $encrypter;
    protected $db;


    /**
     * Constructor
     * 
     * Fungsi ini akan dijalankan saat instance class ini dibuat
     * Fungsi ini digunakan untuk menginisialisasi model yang akan digunakan
     * 
     * @return void
     */
    public function __construct()
    {
        // Buat instance dari model yang digunakan
        $this->book = new BooksModel();
        $this->loan = new LoansModel();
        $this->encrypter = \Config\Services::encrypter();
        $this->db = \Config\Database::connect();
    }


    /**
     * Tampilkan halaman dashboard buku
     * 
     * Fungsi ini akan menampilkan halaman dashboard buku yang berisi list buku
     * Fungsi ini akan mengirimkan data buku yang tersedia ke view
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function index()
    {
        $data['books'] = $this->book->getAllBook();
        dd($data);
    }


    /**
     * Tampilkan halaman detail buku
     * 
     * Fungsi ini akan menampilkan halaman detail buku yang berisi data buku
     * Fungsi ini akan mengirimkan data buku dan jumlah peminjaman yang tersedia ke view
     * 
     * @param string $book_id id buku yang akan ditampilkan
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     * 
     */



    public function viewDetailBook($book_id)
    {
        $id_book = $this->decryptId($book_id);
        $book_detail = $this->book->getDataById($id_book);
        $count_loans = $this->loan->getCountLoanByIdBook($id_book);

        $data = [
            'book_detail' => $book_detail,
            'count_loans' => $count_loans
        ];

        return view('Content/MasterData/buku', $data);
    }

    public function addBook()
    {
        $validationRules = $this->getValidationRules(false);

        if (!$this->validate($validationRules)) {
            return ResponHelper::handlerErrorResponJson($this->validator->getErrors(), 400);
        }

        $data_book = $this->request->getPost();

        try {
            return ResponHelper::handlerSuccessResponJson($data_book, 201);
        } catch (\Exception $e) {
            return ResponHelper::handlerErrorResponJson($e->getMessage(), 500);
        }
    }

    public function editBook()
    {
        // edit book
    }
    public function deleteBook()
    {
        // delete data 
    }

    private function decryptId($id_book)
    {
        $decode_id = $this->encrypter->decrypt(base64_decode($id_book));
        return $decode_id;
    }


    /**
     * Fungsi untuk mendapatkan aturan validasi inputan
     * 
     * Fungsi ini akan mengembalikan aturan validasi inputan
     * yang akan digunakan untuk validasi inputan
     * 
     * @param boolean $is_update apakah data yang akan divalidasi
     * adalah data yang akan diupdate atau tidak
     * 
     * @return array Aturan validasi inputan
     */
    private function getValidationRules($is_update = false)
    {
        return [
            'category_id' => [
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => 'Kategori wajib dipilih.',
                    'is_natural_no_zero' => 'Kategori harus berupa angka positif dan tidak nol.',
                ],
            ],
            'book_name' => [
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => 'Nama buku wajib diisi.',
                    'max_length' => 'Nama buku tidak boleh lebih dari 255 karakter.',
                ],
            ],
            'isbn' => [
                'rules' => 'required|max_length[20]|numeric',
                'errors' => [
                    'required' => 'Nomor ISBN wajib diisi.',
                    'max_length' => 'Nomor ISBN tidak boleh lebih dari 20 karakter.',
                    'numeric' => 'Nomor ISBN hanya boleh berisi angka.',
                ],
            ],
            'author.*' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Penulis wajib diisi.',
                ],
            ],
            'publisher' => [
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => 'Penerbit wajib diisi.',
                    'max_length' => 'Nama penerbit tidak boleh lebih dari 255 karakter.',
                ],
            ],
            'year_published' => [
                'rules' => 'required|is_natural|exact_length[4]',
                'errors' => [
                    'required' => 'Tahun terbit wajib diisi.',
                    'is_natural' => 'Tahun terbit harus berupa angka positif.',
                    'exact_length' => 'Tahun terbit harus terdiri dari 4 digit.',
                ],
            ],
            'description' => [
                'rules' => 'permit_empty|max_length[1000]',
                'errors' => [
                    'max_length' => 'Deskripsi tidak boleh lebih dari 1000 karakter.',
                ],
            ],
            'total_books' => [
                'rules' => 'required|is_natural',
                'errors' => [
                    'required' => 'Jumlah total buku wajib diisi.',
                    'is_natural' => 'Jumlah total buku harus berupa angka positif.',
                ],
            ],
            'total_copies' => [
                'rules' => 'is_natural',
                'errors' => [
                    'is_natural' => 'Jumlah total salinan harus berupa angka positif.',
                ],
            ],
        ];
    }
}
