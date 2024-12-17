<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    /*
    1. nama anggota (string) : nama_anggota
    2. nis (int) : nomor_induk
    3. alamat (string) : Alamat
    4. nomor hp (int) : nomor_hp 
    5. tanggal bergabung (string) : tanggal_bergabung
    
    */

    /* 
        dari parameter dan varaibel di atas 
        saya ingin kau buat fitur
         menampilkan data, tambah data ,edit data dan delete data
        */
    public function viewUser()
    {
        // tampil data
    }
    public function AddUser()
    {
        // tambah data
    }

    public function EditUser()
    {
        // edit data
    }
    public function DeleteUser()
    {
        // delete data 
    }
}
