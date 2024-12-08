<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\UsersModel;
use App\Helpers\ResponHelper;

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

    public function index(): string
    {
        return view('welcome_message');
    }

    private function getJWT($id, $username)
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
            return ResponHelper::handlerErrorResponJson($this->validator->getErrors(), 400);
        }

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user_data = filter_var($username, FILTER_VALIDATE_EMAIL)
            ? $this->user->getDataByEmail($username)
            : $this->user->getDataByUsername($username);

        if ($user_data && password_verify($password, $user_data['password'])) {
            $token = $this->getJWT($user_data['id'], $user_data['username']);

            $params = ['id_user' => $token];
            session()->set($params);
            // return ResponHelper::handlerSuccessResponJson('Berhasil Login', 200, $data);
            return redirect()->to('home/profile');
        }

        return ResponHelper::handlerErrorResponJson('Username atau password salah', 400);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }

    public function profile()
    {
        $id_user = session('id_user');
        $decode_id = $this->encrypter->decrypt(base64_decode($id_user['id']));

        $data['user'] = $this->user->where('id', $decode_id)->first();
        // dd($id_user, $decode_id);


        // return ResponHelper::handlerSuccessResponJson('User profile retrieved successfully', 200, $userData);
        return view('test', $data);
    }
}
