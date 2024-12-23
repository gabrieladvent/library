<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\UsersModel;
use App\Helpers\ResponHelper;
use CodeIgniter\CLI\Console;

class Home extends BaseController
{
    protected $user;
    protected $encrypter;
    // $decodedResult = $this->encrypter->decrypt(base64_decode($id_encrypt));

    public function __construct()
    {
        $this->user = new UsersModel();
        $this->encrypter = \Config\Services::encrypter();
    }

    public function index()
    {
        $data = [];
        // Cek apakah pengguna sudah login
        if (session('id_user')) {
            $id_user = session('id_user');
            // Dekripsi ID pengguna
            $decode_id = $this->encrypter->decrypt(base64_decode($id_user['id']));

            // Ambil data pengguna dari database
            $data['user'] = $this->user->where('id', $decode_id)->first();
        }
        return view('auth/login', $data);  // Pastikan data dikirim ke view
    }

    private function getToken($id, $username)
    {
        $id_encrypt = base64_encode($this->encrypter->encrypt($id));

        $payload = [
            'id' => $id_encrypt,
            'username' => $username
        ];

        return $payload;
    }

    public function login_process()
    {
        if (!$this->validate([
            'username' => 'required',
            'password' => 'required',
        ])) {
            session()->setFlashdata('error', 'Username dan password wajib diisi.');
            return redirect()->back();
        }

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user_data = filter_var($username, FILTER_VALIDATE_EMAIL)
            ? $this->user->getDataByEmail($username)
            : $this->user->getDataByUsername($username);

        if ($user_data && password_verify($password, $user_data['password'])) {
            $token = $this->getToken($user_data['id'], $user_data['username']);
            session()->set(['id_user' => $token]);

            session()->setFlashdata('success', 'Login berhasil!  ');
            return redirect()->to('home/dashboard');
        }

        session()->setFlashdata('error', 'Username atau password salah.');
        return redirect()->back();
    }

    public function logout()
    {
        session()->destroy();
        setcookie(session_name(), '', time() - 3600, '/');  // Menghapus cookie sesi di browser


        return redirect()->to(base_url('/'));
    }

    public function Dashboard()
    {
        $id_user = session('id_user');
        $decode_id = $this->encrypter->decrypt(base64_decode($id_user['id']));

        $data['user'] = $this->user->where('id', $decode_id)->first();
        // dd($id_user, $decode_id);


        // return ResponHelper::handlerSuccessResponJson('User profile retrieved successfully', 200, $userData);
        return view('Dashboard', $data);
    }

    public function Kategori()
    {
        $id_user = session('id_user');
        $decode_id = $this->encrypter->decrypt(base64_decode($id_user['id']));

        $data['user'] = $this->user->where('id', $decode_id)->first();

        return view('Content/MasterData/kategori', $data);
    }
    public function Buku()
    {
        $id_user = session('id_user');
        $decode_id = $this->encrypter->decrypt(base64_decode($id_user['id']));

        $data['user'] = $this->user->where('id', $decode_id)->first();

        return view('Content/MasterData/buku', $data);
    }
    public function Anggota()
    {
        $id_user = session('id_user');
        $decode_id = $this->encrypter->decrypt(base64_decode($id_user['id']));

        $data['user'] = $this->user->where('id', $decode_id)->first();

        return view('Content/MasterData/anggota', $data);
    }
}
