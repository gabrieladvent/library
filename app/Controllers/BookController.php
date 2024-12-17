<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpParser\Node\Expr\FuncCall;

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

    public function viewBook()
    {
        // tampil data
    }
    public function AddBook()
    {
        // tambah data
    }

    public function EditBook()
    {
        // edit book
    }
    public function DeleteBook()
    {
        // delete data 
    }
}
