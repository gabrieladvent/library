<?php

namespace App\Controllers;

use App\Models\BooksModel;
use App\Models\LoansModel;
use App\Models\UsersModel;
use App\Helpers\ResponHelper;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LoansController extends BaseController
{


    protected $user;
    protected $encrypter;
    protected $loans;
    protected $db;
    protected $book;
    public function __construct()
    {
        // Buat instance dari model yang digunakan
        $this->user = new UsersModel();
        $this->loans = new LoansModel();
        $this->book = new BooksModel();
        $this->db = \Config\Database::connect();
        $this->encrypter = \Config\Services::encrypter();
    }

    /*
    1. nama anggota (string) : nama_anggota
    2. nama buku (string) : nama_buku
    3. tangal peminjaman (string) : tanggal_pinjam
    4. tanggal pengembalian (string) : tanggal_pengembalian
    5. jumlah buku (int) : jml_buku
    6. status (saya belum tau parameter yang cocok) : Status
    */

    /* 
        dari parameter dan varaibel di atas 
        saya ingin kau buat fitur
        menampilkan data, tambah data ,edit data dan delete data
        */

    public function viewLoans()
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


        $data['user'] = $this->user->getDataUserById($decode_id);
        $data['loans'] = $this->loans->getAllLoans();
        // dd(empty($data['loans']));
        log_message("info", "data loans" . json_encode($data));
        return view("Content/PeminjamanBuku/PinjamBuku", $data);
    }

    public function addLoans()
    {
        $id_user = session('id_user');
        if (!$id_user || !isset($id_user['id'])) {
            return ResponHelper::handlerErrorResponRedirect('loans/list', 'Data tidak valid. id user');
        }

        $decode_id = $this->encrypter->decrypt(base64_decode($id_user['id']));
        if($this->user->getDataUserById($decode_id) == null){
            return ResponHelper::handlerErrorResponRedirect('loans/list', 'Data tidak valid. tdk dapat id'); 
        }

        $data_loans = $this->request->getPost();
        $data_loans['pelayan_id'] = $decode_id;
        $quantity = $data_loans['quantity'];
        $data_available = $data_loans['available_books'] - $quantity;
        unset($data_loans['available_books']);

        try {
            $this->db->transStart();

            $this->loans->insert($data_loans);
            $this->book->update($data_loans['book_id'], ['available_books' => $data_available]);

            $this->db->transComplete();
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return ResponHelper::handlerErrorResponRedirect('loans/list', 'Data tidak valid: ' . $th->getMessage());
        }

    }

    public function editLoans()
    {
        // edit data
    }
    public function deleteLoans()
    {
        // delete data 
    }
}
