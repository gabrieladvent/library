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
        $daily_loans = $this->getChartLine();
        $classes_loans = $this->getChartBar();
        $books_category = $this->getChartPie();


        $data = [
            'user' => $this->user->getDataUserById($decode_id),
            'count_newborrower' => $this->loans->countNewLoans() ?? 0,
            'count_book' => $this->books->countAllBook() ?? 0,
            'count_users' => [
                'count_user' => $this->user->countUserByRole('User') ?? 0,
                'count_admin' => $this->user->countUserByRole('Admin') ?? 0,
            ],
            'labels_line' => json_encode($daily_loans['labels'] ?? 0),
            'loan_data_line' => json_encode($daily_loans['statusData'] ?? 0),

            'labels_bar' => json_encode($classes_loans['labels']),
            'loan_data_bar' => json_encode($classes_loans['data']),

            'labels_pie' => json_encode($books_category['labels']),
            'loan_data_pie' => json_encode($books_category['data']),
        ];

        return view('Dashboard', $data);
    }

    private function getChartLine()
    {
        $count_daily_loans = $this->loans->countDailyLoansByStatus();

        $hariInggris = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        $hariIndonesia = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

        $labels = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $statusData = [
            'Dipinjam' => array_fill(0, 7, 0),
            'Diperpanjang' => array_fill(0, 7, 0),
            'Dikembalikan' => array_fill(0, 7, 0),
            'Terlambat' => array_fill(0, 7, 0),
        ];

        foreach ($count_daily_loans as $row) {
            $loan_day = str_replace($hariInggris, $hariIndonesia, $row['loan_day']);
            $dayIndex = array_search($loan_day, $labels);
            if ($dayIndex !== false) {
                $statusData[$row['status']][$dayIndex] = (int) $row['total'];
            }
        }

        return ['labels' => $labels, 'statusData' => $statusData];
    }

    private function getChartBar()
    {
        $count_loans_by_class = $this->loans->countLoansByClass();

        $classLabels = [];
        $classData = [];

        foreach ($count_loans_by_class as $row) {
            $classLabels[] = $row['class_name']; 
            $classData[] = (int) $row['total']; 
        }

        return ['labels' => $classLabels, 'data' => $classData];
    }

    private function getChartPie()
    {
        $count_books_by_category = $this->books->countBooksByCategory();

        $categoryLabels = [];
        $categoryData = [];

        foreach ($count_books_by_category as $row) {
            $categoryLabels[] = $row['category_name']; 
            $categoryData[] = (int) $row['total'];
        }

        return ['labels' => $categoryLabels, 'data' => $categoryData];
    }
}
