<?php

namespace App\Controllers;

use App\Models\BooksModel;
use App\Models\LoansModel;
use App\Helpers\ResponHelper;
use App\Models\CategoriesModel;
use App\Controllers\BaseController;

class BookController extends BaseController
{
    protected $book;
    protected $loan;
    protected $encrypter;
    protected $db;
    protected $category;


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
        $this->category = new CategoriesModel();
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
        return view('book/index', $data);
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

        return view('book/detail', $data);
    }

    /**
     * Tambahkan data buku
     * 
     * Fungsi ini akan menambahkan data buku berdasarkan data yang dikirimkan
     * Fungsi ini akan mengembalikan respon dalam format json
     * Jika data yang dikirimkan tidak valid, maka akan mengembalikan respon error 400
     * Jika data yang dikirimkan valid, maka akan mengembalikan respon success 201
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function addBook()
    {
        $validationRules = $this->getValidationRules(false);

        if (!$this->validate($validationRules)) {
            return ResponHelper::handlerErrorResponJson($this->validator->getErrors(), 400);
        }

        $data_book = $this->request->getPost();
        $book_name = $this->request->getPost('book_name');

        try {
            $handle_sampul = $this->uploadFiles($book_name);
            $author_json = json_encode($this->request->getPost('author'));
            $data_book['author'] = $author_json;
        } catch (\Throwable $th) {
            $message = 'Gagal mengupload file: ' . $th->getMessage();
            return ResponHelper::handlerErrorResponJson($message, 400);
        }

        try {
            $data_book = array_merge($data_book, $handle_sampul);
            $this->book->insert($data_book);
            return ResponHelper::handlerSuccessResponJson($data_book, 201);
        } catch (\Exception $e) {
            return ResponHelper::handlerErrorResponJson($e->getMessage(), 500);
        }
    }


    /**
     * Edit data buku
     * 
     * Fungsi ini akan mengedit data buku berdasarkan data yang dikirimkan
     * Fungsi ini akan mengembalikan respon dalam format json
     * Jika data yang dikirimkan tidak valid, maka akan mengembalikan respon error 400
     * Jika data yang dikirimkan valid, maka akan mengembalikan respon success 200
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function editBook()
    {
        $data_book = $this->request->getPost();
        $id_book = $this->request->getPost('id_book');
        $data_book_from_db = $this->book->getDataById($id_book);

        if (empty($data_book) || !$data_book_from_db) {
            log_message('error', 'Request data is empty.');
            return ResponHelper::handlerErrorResponJson('Data tidak valid.', 400);
        }

        $validationRules = $this->getValidationRules(true);
        if (empty($validationRules)) {
            log_message('error', 'Validation rules are empty.');
            return ResponHelper::handlerErrorResponJson('Kesalahan server internal.', 500);
        }

        try {
            $this->db->transStart();

            $cover_book = $this->uploadFiles($this->request->getPost('book_name'));
            $author_json = json_encode($this->request->getPost('author'));

            $data_book['author'] = $author_json;
            $data_book = array_merge($data_book, $cover_book);

            $updated = $this->book->update($id_book, $data_book);
            $this->db->transComplete();

            if (!$updated) {
                $this->db->transRollback();
                return ResponHelper::handlerErrorResponJson(['error' => 'Tidak ada data yang di edit'], 400);
            }

            return ResponHelper::handlerSuccessResponJson($data_book, 200);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return ResponHelper::handlerErrorResponJson($th->getMessage(), 500);
        }
    }


    /**
     * Menghapus data buku berdasarkan id buku yang dikirimkan
     * 
     * Fungsi ini akan menghapus data buku yang memiliki id buku yang sama dengan
     * parameter yang dikirimkan
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function deleteBook()
    {
        $id_book = $_GET['books'] ?? null;
        if (empty($id_book)) {
            return ResponHelper::handlerErrorResponJson('ID buku wajib diisi.', 400);
        }

        $id_book = $this->decryptId($id_book);
        $book = $this->book->getDataById($id_book);
        if (empty($book)) {
            return ResponHelper::handlerErrorResponJson('ID buku tidak valid.', 400);
        }

        $loans = $this->loan->getCountLoanByIdBook($id_book);
        if ($loans > 0) {
            return ResponHelper::handlerErrorResponJson('Buku sedang dipinjam.', 400);
        }

        try {
            $this->db->transStart();

            $cover_path = $book['cover_img'];
            if (!empty($cover_path)) {
                unlink($cover_path);
            }

            $deleted = $this->book->delete($id_book);
            $this->db->transComplete();

            if (!$deleted) {
                $this->db->transRollback();
                return ResponHelper::handlerErrorResponJson(['error' => 'Tidak ada data yang dihapus'], 400);
            }
            return ResponHelper::handlerSuccessResponJson(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return ResponHelper::handlerErrorResponJson($th->getMessage(), 500);
        }
    }


    /**
     * Menampilkan halaman list kategori
     * 
     * Fungsi ini akan menampilkan halaman yang berisi list kategori
     * Fungsi ini akan mengirimkan data kategori ke view
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getAllCategory()
    {
        $all_category = $this->category->getAllCategory();
        $data['all_category'] = $all_category;

        return view('content/MasterData/kelas', $data);
    }


    /**
     * Menambahkan kategori baru
     * 
     * Fungsi ini akan menambahkan kategori baru berdasarkan data yang dikirimkan
     * Fungsi ini akan mengembalikan respon dalam format json
     * Jika data yang dikirimkan tidak valid, maka akan mengembalikan respon error 400
     * Jika data yang dikirimkan valid, maka akan mengembalikan respon success 201
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function addCategory()
    {
        $data_category = $this->request->getPost();
        $isExists = $this->category->checkName($data_category['category_name']);

        if ($isExists) {
            return ResponHelper::handlerErrorResponJson('Kategori sudah ada', 400);
        }

        try {
            $this->category->insert($data_category);
            return ResponHelper::handlerSuccessResponJson($data_category, 201);
        } catch (\Throwable $th) {
            return ResponHelper::handlerErrorResponJson([$th->getMessage(), $th->getTraceAsString()], 400);
        }
    }


    /**
     * Menghapus kategori berdasarkan id kategori yang dikirimkan
     * 
     * Fungsi ini akan menghapus kategori yang memiliki id kategori yang sama dengan parameter
     * Fungsi ini akan mengembalikan respon dalam format json
     * Jika data yang dikirimkan tidak valid, maka akan mengembalikan respon error 400
     * Jika data yang dikirimkan valid, maka akan mengembalikan respon success 200
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function deleteCategory()
    {
        $category_id = $_GET['category'] ?? null;
        $id_decrypt = $this->decryptId($category_id);

        $check_books = $this->book->getDataByCategoryId($id_decrypt);
        if (!empty($check_books)) {
            return ResponHelper::handlerErrorResponJson(['error' => 'Class still has users'], 400);
        }

        try {
            $this->db->transStart();
            $this->category->delete($id_decrypt);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return ResponHelper::handlerErrorResponJson('Database transaction failed', 500);
            }

            return ResponHelper::handlerSuccessResponJson(['message' => 'category deleted successfully'], 200);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return ResponHelper::handlerErrorResponJson($e->getMessage(), 500);
        }
    }


    /**
     * Fungsi untuk mengedit data kategori
     * 
     * Fungsi ini akan mengedit data kategori berdasarkan id kategori yang dikirimkan
     * Fungsi ini akan mengembalikan respon dalam format json
     * Jika data yang dikirimkan tidak valid, maka akan mengembalikan respon error 400
     * Jika data yang dikirimkan valid, maka akan mengembalikan respon success 200
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function editCategory()
    {
        $id_category = $_GET['category'] ?? null;
        $id_decrypt = $this->decryptId($id_category);
        $category = $this->category->find($id_decrypt);

        if (empty($category)) {
            return ResponHelper::handlerErrorResponJson(['error' => 'Class not found'], 404);
        }

        $data_category = $this->request->getPost();
        $isExist = $this->category->checkName($data_category['category_name']);

        if ($isExist) {
            return ResponHelper::handlerErrorResponJson(['error' => 'Nama kelas sudah ada'], 400);
        }

        $this->category->update($id_decrypt, $data_category);
        return ResponHelper::handlerSuccessResponJson($data_category, 200);
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
                'rules' => $is_update ? 'is_natural_no_zero'
                    : 'required|is_natural_no_zero',
                'errors' => [
                    'required' => 'Kategori wajib dipilih.',
                    'is_natural_no_zero' => 'Kategori harus berupa angka positif dan tidak nol.',
                ],
            ],
            'book_name' => [
                'rules' => $is_update ? 'max_length[255]'
                    : 'required|max_length[255]',
                'errors' => [
                    'required' => 'Nama buku wajib diisi.',
                    'max_length' => 'Nama buku tidak boleh lebih dari 255 karakter.',
                ],
            ],
            'isbn' => [
                'rules' => $is_update ? 'max_length[20]|numeric'
                    : 'required|max_length[20]|numeric',
                'errors' => [
                    'required' => 'Nomor ISBN wajib diisi.',
                    'max_length' => 'Nomor ISBN tidak boleh lebih dari 20 karakter.',
                    'numeric' => 'Nomor ISBN hanya boleh berisi angka.',
                ],
            ],
            'author.*' => [
                'rules' => $is_update ? 'permit_empty' : 'required',
                'errors' => [
                    'required' => 'Penulis wajib diisi.',
                ],
            ],
            'publisher' => [
                'rules' => $is_update ? 'max_length[255]' : 'required|max_length[255]',
                'errors' => [
                    'required' => 'Penerbit wajib diisi.',
                    'max_length' => 'Nama penerbit tidak boleh lebih dari 255 karakter.',
                ],
            ],
            'year_published' => [
                'rules' => $is_update ? 'is_natural|exact_length[4]' : 'required|is_natural|exact_length[4]',
                'errors' => [
                    'required' => 'Tahun terbit wajib diisi.',
                    'is_natural' => 'Tahun terbit harus berupa angka positif.',
                    'exact_length' => 'Tahun terbit harus terdiri dari 4 digit.',
                ],
            ],
            'description' => [
                'rules' => $is_update ? 'max_length[1000]' : 'max_length[1000]',
                'errors' => [
                    'max_length' => 'Deskripsi tidak boleh lebih dari 1000 karakter.',
                ],
            ],
            'total_books' => [
                'rules' => $is_update ? 'is_natural' : 'required|is_natural',
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
            'cover_img' => [
                'rules' => $is_update
                    ? 'permit_empty|mime_in[cover_img,image/png,image/jpg,image/jpeg]|max_size[cover_img,2048]'
                    : 'uploaded[cover_img]|mime_in[cover_img,image/png,image/jpg,image/jpeg]|max_size[cover_img,2048]',
                'errors' => [
                    'uploaded' => 'Silakan unggah Sampul Buku.',
                    'mime_in' => 'Sampul Buku harus berupa gambar PNG, JPG, atau JPEG.',
                    'max_size' => 'Sampul Buku tidak boleh lebih dari 2MB.',
                ]
            ],
        ];
    }


    /**
     * Mengupload file sampul buku ke server
     * 
     * @param string $book_name Nama buku
     * @return array Array yang berisi nama file yang diupload
     */
    private function uploadFiles($book_name)
    {
        $field = "cover_img";
        $uploadedFiles = [];

        if ($file = $this->request->getFile($field)) {
            if ($file->isValid() && !$file->hasMoved()) {
                $fileName = $this->generateFileName($file, $book_name, $field);
                $file->move($field . '/', $fileName);
                $uploadedFiles[$field] = $field . '/' . $fileName;
            }
        }
        return $uploadedFiles;
    }


    /**
     * Mengenerate nama file yang unik untuk file yang diupload
     * 
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file File yang diupload
     * @param string $book_name Nama buku
     * @param string $field Nama field yang diupload
     * @return string Nama file yang diupload
     */
    private function generateFileName($file, $book_name, $field)
    {
        $ext = $file->getClientExtension();

        // Buat nama file yang unik
        // Format: nama_buku_field_waktu_ekstensi
        // Contoh: buku_satu_cover_img_1627209312.png
        $filename = strtolower($book_name . '_' . $field . '_' . time() . '.' . $ext);

        return $filename;
    }
}
