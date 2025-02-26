<?php

namespace App\Controllers;

use Config\Database;
use Config\Services;
use App\Models\BooksModel;
use App\Models\LoansModel;
use App\Models\UsersModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ReportController extends BaseController
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

        $data['user'] = $this->user->getDataUserById($decode_id);
        $data['loans'] = $this->loans->getAllLoans();
        // dd($data);
        return view("Content/Laporan/laporan", $data);
    }
}
