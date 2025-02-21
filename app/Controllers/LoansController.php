<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoansModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsersModel;

class LoansController extends BaseController
{


    protected $user;
    protected $encrypter;
    protected $loans;
    public function __construct()
    {
        // Buat instance dari model yang digunakan
        $this->user = new UsersModel();
        $this->loans = new LoansModel();
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

        $loans = $this->loans->getAllLoans();


        $data['user'] = $this->user->getDataUserById($decode_id);
        $data['loans'] = $loans;
        log_message("info", "data loans" . json_encode($data));
        return view("Content/PeminjamanBuku/PinjamBuku", $data);
    }
    public function addLoans()
    {
        // tambah data
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
