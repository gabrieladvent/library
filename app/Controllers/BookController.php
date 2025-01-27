<?php

namespace App\Controllers;

use App\Models\BooksModel;
use App\Models\LoansModel;
use App\Helpers\ResponHelper;
use App\Controllers\BaseController;
use App\Models\UsersModel;

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

        return view('Content/MasterData/buku', $data);
    }


    // public function index()
    // {
    //     $id_user = session('id_user');
    //     if (!$id_user || !isset($id_user['id'])) {
    //         return redirect()->back()->with('error', 'Session tidak valid');
    //     }

    //     try {
    //         $decode_id = $this->encrypter->decrypt(base64_decode($id_user['id']));
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Dekripsi ID gagal');
    //     }

    //     // Mengambil data buku
    //     $books = $this->book->getAllBook();

    //     // Mengembalikan data sebagai JSON
    //     return $this->response->setJSON($books);
    // }


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
                return ResponHelper::handlerSuccessResponJson($data_book, 201);  // Response sukses
            } else {
                return ResponHelper::handlerErrorResponJson('Gagal menyimpan data', 500);
            }
        } catch (\Exception $e) {
            return ResponHelper::handlerErrorResponJson($e->getMessage(), 500);
        }
    }


    public function editBook()
    {
        $id_book = $_GET['books'] ?? null;
        if (empty($id_book)) {
            return ResponHelper::handlerErrorResponJson('ID buku wajib diisi.', 400);
        }

        $data_book = $this->request->getPost();
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
            
            $image = $this->request->getFile('cover_img');
            $cover_img_old = $data_book_from_db['cover_img'];
            $cover_img_new = $this->uploadFiles($image, $this->request->getPost('fullname'));

            if ($cover_img_old != $cover_img_new['cover_img']) {
                if (file_exists($cover_img_old)){
                    unlink($cover_img_old);
                }
            }
            
            $author = $this->request->getPost('author');
            $convert_array = array($author);
            $author_json = json_encode($convert_array);

            $data_book['author'] = $author_json;
            $data_book = array_merge($data_book, $cover_img_new);

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


    public function deleteBook()
    {
        $id_book = $_GET['books'] ?? null;
        if (empty($id_book)) {
            return ResponHelper::handlerErrorResponJson('ID buku wajib diisi.', 400);
        }
        // $id_book = $this->decryptId($id_book);
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
            'cover_img' => [
                'rules' => 'permit_empty',
                'errors' => [
                    'permit_empty' => 'Sampul buku wajib diisi.',
                ],
            ],
        ];
    }

    /**
     * Mengupload file sampul buku ke server
     * 
     * @param string $book_name Nama buku
     * @return array Array yang berisi nama file yang diupload
     */
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


    // private function uploadFiles($book_name)
    // {
    //     $field = "cover_img";
    //     $uploadedFiles = [];

    //     // Definisikan path upload yang benar
    //     $uploadPath = FCPATH . 'public/uploads/' . $field;  // FCPATH mengarah ke root direktori public

    //     // Cek dan buat direktori jika belum ada
    //     if (!is_dir($uploadPath)) {
    //         if (!mkdir($uploadPath, 0777, true)) {
    //             throw new \RuntimeException('Gagal membuat direktori upload');
    //         }
    //     }

    //     // Ambil file yang diupload
    //     $file = $this->request->getFile($field);

    //     // Validasi file
    //     if (!$file || !$file->isValid()) {
    //         throw new \RuntimeException('File tidak valid atau tidak ditemukan');
    //     }

    //     if ($file->hasMoved()) {
    //         throw new \RuntimeException('File sudah dipindahkan');
    //     }

    //     try {
    //         // Generate nama file
    //         $fileName = $this->generateFileName($file, $book_name, $field);

    //         // Pindahkan file
    //         if ($file->move($uploadPath, $fileName)) {
    //             // Simpan path relatif ke database
    //             $uploadedFiles[$field] = 'uploads/' . $field . '/' . $fileName;

    //             return $uploadedFiles;
    //         } else {
    //             throw new \RuntimeException('Gagal memindahkan file');
    //         }
    //     } catch (\Exception $e) {
    //         log_message('error', 'Upload error: ' . $e->getMessage());
    //         throw new \RuntimeException('Error saat upload file: ' . $e->getMessage());
    //     }
    // }


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
