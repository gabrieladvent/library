<?php

namespace App\Controllers;

use App\Models\BooksModel;
use App\Models\LoansModel;
use App\Models\UsersModel;
use App\Helpers\ResponHelper;
use App\Models\CategoriesModel;
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
    protected $user;
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
        $this->user = new UsersModel();
        $this->book = new BooksModel();
        $this->loan = new LoansModel();
        $this->category = new CategoriesModel();
        $this->encrypter = \Config\Services::encrypter();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $id_user = session('id_user');
        if (!$id_user || !isset($id_user['id'])) {
            return redirect()->back()->with('error', 'Session tidak valid');
        }

        try {
            $decode_id = $this->encrypter->decrypt(base64_decode($id_user['id']));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Dekripsi ID gagal');
        }

        $books = $this->book->getAllBook();

        // Decode author JSON string into an array, then join to string
        foreach ($books as &$book) {
            $book['author'] = implode(', ', json_decode($book['author']));
        }

        $data['books'] = $books;
        $data['user'] = $this->user->getDataUserById($decode_id);
        $data['categories'] = $this->category->getAllCategory();
        // dd($data);

        return view('Content/MasterData/buku', $data);
    }

    public function viewDetailBook($book_id)
    {
        try {
            // Langsung gunakan ID tanpa decode
            $book_detail = $this->book->getDataById($book_id);
            $count_loans = $this->loan->getCountLoanByIdBook($book_id);

            if (!$book_detail) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data buku tidak ditemukan'
                ])->setStatusCode(404);
            }

            // Jika author dalam bentuk JSON string, decode
            if (isset($book_detail['author']) && is_string($book_detail['author'])) {
                $book_detail['author'] = json_decode($book_detail['author'], true);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'book_detail' => $book_detail,
                    'count_loans' => $count_loans
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in viewDetailBook: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data buku'
            ])->setStatusCode(500);
        }
    }


    public function addBook()
    {
        $validationRules = $this->getValidationRules(false);

        if (!$this->validate($validationRules)) {
            return ResponHelper::handlerErrorResponJson($this->validator->getErrors(), 400);
        }

        // Ambil data yang dikirim dari form atau API
        $data_book = $this->request->getPost();

        // Pastikan author diubah menjadi format JSON sebelum disimpan
        if (isset($data_book['author']) && is_array($data_book['author'])) {
            $data_book['author'] = json_encode($data_book['author']);
        }

        try {
            // Simpan data ke database
            $cover_book = $this->uploadFiles($this->request->getFile('cover_img'), $this->request->getPost('book_name'));
            $data_book['cover_img'] = $cover_book['cover_img'];

            if ($this->book->save($data_book)) {
                return ResponHelper::handlerSuccessResponRedirect("book/dashboard", "Data berhasil ditambahkan");
            } else {
                return ResponHelper::handlerSuccessResponRedirect("book/dashboard", "Data gagal ditambahkan");
            }
        } catch (\Exception $e) {
            return ResponHelper::handlerErrorResponJson($e->getMessage(), 500);
        }
    }

    public function editBook()
    {
        $id_book = $_GET['books'];
        if (empty($id_book)) {
            return ResponHelper::handlerErrorResponJson('ID buku wajib diisi.', 400);
        }

        $data_book = $this->request->getPost();
        $data_book_from_db = $this->book->getDataById($id_book);

        if (empty($data_book) || !$data_book_from_db) {
            return ResponHelper::handlerErrorResponJson('Data tidak valid.', 400);
        }


        try {
            $image = $this->request->getFile('cover_img');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $cover_img_new = $this->uploadFiles($image, $data_book['book_name']);

                $cover_img_old = $data_book_from_db['cover_img'];
                if (!empty($cover_img_old) && file_exists($cover_img_old)) {
                    unlink($cover_img_old);
                }

                $data_book = array_merge($data_book, $cover_img_new);
            } else {
                $data_book['cover_img'] = $data_book_from_db['cover_img'];
            }

            $author = $this->request->getPost('author');
            $data_book['author'] = json_encode(is_array($author) ? $author : [$author]);
            $data_book['category_id'] = $data_book['category_name'];
            unset($data_book['category_name']);
            log_message('debug', 'Final data untuk update: ' . print_r($data_book, true));

            $updated = $this->book->update($id_book, $data_book);

            if ($updated === false) {
                throw new \Exception('Gagal mengupdate data buku');
            }

            $this->db->transCommit();
            return ResponHelper::handlerSuccessResponRedirect("book/dashboard", "Data berhasil diedit");
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error saat update buku: ' . $e->getMessage());
            return ResponHelper::handlerErrorResponJson($e->getMessage(), 500);
        } catch (\Throwable $th) {
            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
            }
            log_message('error', 'Error di editBook: ' . $th->getMessage());
            return ResponHelper::handlerErrorResponJson($th->getMessage(), 500);
        }
    }

    public function deleteBook()
    {
        $id_book = $_GET['books'] ?? null;
        if (empty($id_book)) {
            session()->setFlashdata('error', 'ID buku wajib diisi.');
            return ResponHelper::handlerErrorResponJson('Tidak berhasil Dihapus', 400);
        }

        $book = $this->book->getDataById($id_book);
        // return ResponHelper::handlerSuccessResponJson($book, 200);
        if (empty($book)) {
            session()->setFlashdata('error', 'ID buku tidak valid.');
            return ResponHelper::handlerErrorResponJson('Tidak berhasil Dihapus', 400);
        }

        $loans = $this->loan->getCountLoanByIdBook($id_book);
        if ($loans > 0) {
            session()->setFlashdata('error', 'Buku sedang dipinjam.');
            return ResponHelper::handlerErrorResponJson('Tidak berhasil Dihapus', 400);
        }

        try {
            $this->db->transStart();
            $cover_path = $book['cover_img'];
            if (!empty($cover_path) && file_exists($cover_path)) {
                unlink($cover_path);
            }

            $deleted = $this->book->delete($id_book);
            $this->db->transComplete();

            if (!$deleted) {
                $this->db->transRollback();
                session()->setFlashdata('error', 'Tidak berhasil Dihapus');
                return ResponHelper::handlerErrorResponJson('Tidak berhasil Dihapus', 400);
            }

            session()->setFlashdata('success', 'Berhasil Menghapus');
            return ResponHelper::handlerSuccessResponJson('Buku Berhasil Dihapus', 200);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            session()->setFlashdata('error', $th->getMessage());
            return ResponHelper::handlerErrorResponJson('Tidak berhasil Dihapus', 400);
        }
    }

    private function decryptId($id_book)
    {
        $decode_id = $this->encrypter->decrypt(base64_decode($id_book));
        return $decode_id;
    }

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
            'cover_img' => [
                'rules' => 'permit_empty',
                'errors' => [
                    'permit_empty' => 'Sampul buku wajib diisi.',
                ],
            ],
        ];
    }

    private function uploadFiles($photo, $name)
    {
        $field = "cover_img";
        $uploadedFiles = [];
        $file = $photo;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $this->generateFileName($file, $name, $field);
            $file->move($field . '/', $fileName);
            $uploadedFiles[$field] = $field . '/' . $fileName;
        }

        return $uploadedFiles;
    }

    private function generateFileName($file, $book_name, $field)
    {
        $ext = $file->getClientExtension();
        // Buat nama file yang unik
        // Format: nama_buku_field_waktu_ekstensi
        // Contoh: buku_satu_cover_img_1627209312.png
        $filename = strtolower($book_name . '_' . $field . '_' . time() . '.' . $ext);
        return $filename;
    }

    public function getAllCategories()
    {
        $id_user = session('id_user');
        if (!$id_user || !isset($id_user['id'])) {
            return redirect()->back()->with('error', 'Session tidak valid');
        }

        try {
            $decode_id = $this->encrypter->decrypt(base64_decode($id_user['id']));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Dekripsi ID gagal');
        }

        $all_category = $this->category->getAllCategory();
        $data['user'] = $this->user->getDataUserById($decode_id);
        $data['all_category'] = $all_category;

        return view("Content/MasterData/kategori", $data);
    }

    public function viewDetailCategory()
    {
        $id_category = $this->request->getGet('category');
        if (!$id_category) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID kategori tidak ditemukan'
            ])->setStatusCode(400);
        }

        try {
            $id_decrypt = $this->decryptId($id_category);
            $categoryData = $this->category->getDetailCategoryById($id_decrypt);

            if (!$categoryData) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data kategori tidak ditemukan'
                ])->setStatusCode(404);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $categoryData
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in viewDetailCategory: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kategori'
            ])->setStatusCode(500);
        }
    }

    public function addCategory()
    {
        $data_category = $this->request->getPost();
        $isExists = $this->category->checkName($data_category['category_name']);

        if ($isExists) {
            return ResponHelper::handlerErrorResponRedirect('category/all', 'Kategori sudah ada');
        }

        try {
            $this->category->insert($data_category);
            return ResponHelper::handlerSuccessResponRedirect('category/all', 'Kategori berhasil ditambahkan');
        } catch (\Throwable $th) {
            return ResponHelper::handlerErrorResponRedirect('category/all', 'Kategori gagal ditambahkan');
        }
    }

    public function deleteCategory()
    {
        $category_id = $_GET['category'] ?? null;
        $id_decrypt = $this->decryptId($category_id);

        $check_books = $this->book->getDataByCategoryId($id_decrypt);
        if (!empty($check_books)) {
            return ResponHelper::handlerErrorResponJson(['error' => 'Category still has Books'], 400);
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

    public function editCategory()
    {
        $id_category = $_GET['category'] ?? null;
        $id_decrypt = $this->decryptId($id_category);
        $category = $this->category->find($id_decrypt);

        if (empty($category)) {
            return ResponHelper::handlerErrorResponRedirect("category/all", "terjadi kesalahan");
        }

        $data_category = $this->request->getPost();
        $isExist = $this->category->checkName($data_category['category_name']);

        if ($isExist) {
            return ResponHelper::handlerSuccessResponRedirect("category/all", "Data berhasil diedit");
        }

        $this->category->update($id_decrypt, $data_category);
        return ResponHelper::handlerSuccessResponJson($data_category, 200);
    }

    public function getAllBooks()
    {
        $all_books = $this->book->getAllBooksShort();
        return ResponHelper::handlerSuccessResponJson('success', 200, $all_books);
    }

    public function getDataBooks()
    {
        $id_book = $_GET['books'] ?? null;

        $available_books = $this->book->getAvailableBooks($id_book);
        if (empty($available_books)) {
            return ResponHelper::handlerErrorResponJson(['error' => 'Book not found'], 404);
        }
        return ResponHelper::handlerSuccessResponJson('success', 200, $available_books);
    }
}
