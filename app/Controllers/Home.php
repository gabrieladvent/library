<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\UsersModel;
use App\Helpers\ResponHelper;

class Home extends BaseController
{
    // $encrypter = \Config\Services::encrypter();
    // $encrypter = \Config\Services::encrypter();
    // $decodedResult = $encrypter->decrypt(base64_decode($id_message));
    // $encrypter->encrypt($index['id'])));

    protected $user;

    public function __construct()
    {
        $this->user = new UsersModel();
    }
    public function index(): string
    {
        return view('auth/login');
        
    }

    private function getJWT($id, $username)
    {
        $key = getenv("JWT_SECRET");
        if (!$key) {
            throw new \Exception("JWT Secret not set in environment.");
        }

        $iat = time();
        $exp = $iat + (2 * 60 * 60);

        $payload = [
            'iss' => 'ci4-jwt',
            'sub' => 'token_user',
            'iat' => $iat,
            'exp' => $exp,
            'id_user' => $id,
            'user' => $username,
        ];

        return JWT::encode($payload, $key, "HS256");
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
            $data = ['token' => $token, 'user' => $user_data];

            return ResponHelper::handlerSuccessResponJson('Berhasil Login', 200, $data);
        }

        return ResponHelper::handlerErrorResponJson('Username atau password salah', 400);
    }

    public function logout()
    {
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return ResponHelper::handlerErrorResponJson('Authorization token required.', 400);
        }

        $token = $matches[1];

        try {
            $decoded = JWT::decode($token, new Key(getenv('JWT_SECRET'), 'HS256'));
            $expirationTime = $decoded->exp;
            cache()->save("blacklist_$token", true, $expirationTime - time());
            $data = [
                'expirationTime' => $expirationTime,
                'expirationCache' => $expirationTime - time(),
            ];

            return ResponHelper::handlerSuccessResponJson('Logout successful.', 200, $data);
        } catch (\Exception $e) {
            return ResponHelper::handlerErrorResponJson('Invalid token.', 400);
        }
    }
}
