<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LoansController extends BaseController
{

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
        // tampil data
    }
    public function AddLoans()
    {
        // tambah data
    }

    public function EditLoans()
    {
        // edit data
    }
    public function DeleteLoans()
    {
        // delete data 
    }
}
