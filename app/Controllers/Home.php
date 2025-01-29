<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\UsersModel;
use App\Helpers\ResponHelper;
use App\Models\BooksModel;
use App\Models\LoansModel;

class Home extends BaseController
{
    protected $user;
    protected $encrypter;
    protected $books;
    protected $loans;
    // $decodedResult = $this->encrypter->decrypt(base64_decode($id_encrypt));

    public function __construct()
    {
        $this->user = new UsersModel();
        $this->encrypter = \Config\Services::encrypter();
        $this->books = new BooksModel();
        $this->loans = new LoansModel();
    }

    public function index()
    {
        return view('Auth/login');
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
            return ResponHelper::handlerErrorResponRedirect('/', "Username dan password wajib diisi.");
        }

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user_data = filter_var($username, FILTER_VALIDATE_EMAIL)
            ? $this->user->getDataByEmail($username)
            : $this->user->getDataByUsername($username);

        if ($user_data && password_verify($password, $user_data['password'])) {
            $token = $this->getToken($user_data['id'], $user_data['username']);

            session()->set(['id_user' => $token]);
            return ResponHelper::handlerSuccessResponRedirect('home/dashboard', "Login Berhasil");
        }
        return ResponHelper::handlerErrorResponRedirect('/', "Username atau password salah.");
    }

    public function logout()
    {
        session()->destroy();
        setcookie(session_name(), '', time() - 3600, '/');

        return redirect()->to(base_url('/'));
    }

    public function Dashboard()
    {
        $id_user = session('id_user');
        $decode_id = $this->encrypter->decrypt(base64_decode($id_user['id']));

        $data = [
            'user' => $this->user->getDataUserById($decode_id),
            'count_newborrower' => $this->loans->countNewLoans() ?? 0,
            'count_book' => $this->books->countAllBook() ?? 0,
            'count_users' => [
                'count_user' => $this->user->countUserByRole('User') ?? 0,
                'count_admin' => $this->user->countUserByRole('Admin') ?? 0,
            ],
        ];

        return view('Dashboard', $data);
    }
}
