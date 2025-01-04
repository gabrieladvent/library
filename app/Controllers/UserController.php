<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponHelper;
use App\Models\BiodataUsersModel;
use App\Models\BooksModel;
use App\Models\CategoriesModel;
use App\Models\ClassesModel;
use App\Models\LoansModel;
use App\Models\UsersModel;

class UserController extends BaseController
{
    protected $user;
    protected $biodata;
    protected $loan;
    protected $books;
    protected $class;
    protected $category;
    protected $encrypter;
    protected $db;

    /**
     * Constructor
     * 
     * Fungsi ini akan dijalankan saat instance class ini dibuat
     * Fungsi ini digunakan untuk menginisialisasi model yang akan digunakan
     * 
     * @return void
     */
    public function __construct()
    {
        // Buat instance dari model yang digunakan
        $this->user = new UsersModel();
        $this->biodata = new BiodataUsersModel();
        $this->loan = new LoansModel();
        $this->books = new BooksModel();
        $this->class = new ClassesModel();
        $this->category = new CategoriesModel();
        $this->encrypter = \Config\Services::encrypter();
        $this->db = \Config\Database::connect();
    }

    /**
     * Tampilkan halaman profile
     * 
     * Fungsi ini akan menampilkan halaman profile user yang sedang login
     * Fungsi ini akan mengirimkan data user yang sedang login ke view
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function index()
    {
        $id_user = 1;
        $data['profile_user'] = $this->user->getDetailUserById($id_user);
        dd($data);

        return view('profile', $data);
    }


    /**
     * Menampilkan halaman list user
     * 
     * Fungsi ini akan menampilkan halaman yang berisi list dari user yang terdaftar
     * Fungsi ini akan mengirimkan 2 data ke view, yaitu list_admin dan list_users
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function listUser($type)
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

        $data['list_user'] = $this->user->getAllRoleByRole($type === 'Admin' ? 'Admin' : 'User');
        $data['user'] = $this->user->getDataUserById($decode_id);

        if (!$data['user']) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan');
        }

        $view = $type === 'Admin' ? 'content/Admin/admin' : 'content/MasterData/anggota';
        return view($view, $data);
    }   
    /**
     * Menampilkan halaman detail user
     * 
     * Fungsi ini akan menampilkan halaman yang berisi detail user
     * Fungsi ini akan mengirimkan 2 data ke view, yaitu detail_user dan users_loans
     * 
     * @param string $id_user id user yang akan ditampilkan
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function viewDetailUser()
    {
        $id_user = $_GET['users'] ?? null;

        $id_decrypt = $this->decryptId($id_user);

        $data['detail_user'] = $this->user->getDetailUserById($id_decrypt);
        $data['users_loans'] = $this->loan->getLoanByIdUser($id_decrypt);

        return view('detail_user', $data);
    }


    /**
     * Fungsi untuk menambahkan user baru
     * 
     * Fungsi ini akan menambahkan user baru berdasarkan data yang dikirimkan
     * Fungsi ini akan mengembalikan respon dalam format json
     * Jika data yang dikirimkan tidak valid, maka akan mengembalikan respon error 400
     * Jika data yang dikirimkan valid, maka akan mengembalikan respon success 201
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function addUser()
    {
        $validationRules = $this->getValidationRules(false);

        if (!$this->validate($validationRules)) {
            return ResponHelper::handlerErrorResponJson($this->validator->getErrors(), 400);
        }

        $data_user = $this->request->getPost();

        try {
            $insert_user = $this->insertUser($data_user);
            return ResponHelper::handlerSuccessResponJson(['user' => $insert_user[0], 'biodata' => $insert_user[1]], 201);
        } catch (\Exception $e) {
            return ResponHelper::handlerErrorResponJson($e->getMessage(), 500);
        }
    }


    /**
     * Fungsi untuk mengedit data user yang sudah ada
     * 
     * Fungsi ini akan mengedit data user berdasarkan id user yang dikirimkan
     * Fungsi ini akan mengembalikan respon dalam format json
     * Jika data yang dikirimkan tidak valid, maka akan mengembalikan respon error 400
     * Jika data yang dikirimkan valid, maka akan mengembalikan respon success 200
     * 
     * @param int $id_user id user yang akan di edit
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function editUser($id_user)
    {
        $data_user = $this->request->getPost();
        if (empty($data_user)) {
            log_message('error', 'Request data is empty.');
            return ResponHelper::handlerErrorResponJson('Data tidak valid.', 400);
        }

        $validationRules = $this->getValidationRules(true);
        if (empty($validationRules)) {
            log_message('error', 'Validation rules are empty.');
            return ResponHelper::handlerErrorResponJson('Kesalahan server internal.', 500);
        }

        try {
            $updatedData = $this->updateUser($id_user, $data_user);

            if (!$updatedData) {
                return ResponHelper::handlerErrorResponJson(['error' => 'Tidak ada data yang di edit'], 400);
            }

            return ResponHelper::handlerSuccessResponJson([
                'user' => $updatedData[0],
                'biodata' => $updatedData[1],
            ], 200);
        } catch (\Exception $e) {
            return ResponHelper::handlerErrorResponJson($e->getMessage(), 500);
        }
    }


    /**
     * Fungsi untuk menghapus data user berdasarkan id user yang dikirimkan
     * 
     * Fungsi ini akan menghapus data user yang memiliki id user yang sama dengan parameter
     * Fungsi ini akan mengembalikan respon dalam format json
     * Jika data yang dikirimkan tidak valid, maka akan mengembalikan respon error 400
     * Jika data yang dikirimkan valid, maka akan mengembalikan respon success 200
     * 
     * @param int $id_user id user yang akan dihapus
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function deleteUser($id_user)
    {
        $id_decrypt = $this->decryptId($id_user);

        $check_loans = $this->loan->getAvailableLoans($id_decrypt);
        if (!empty($check_loans)) {
            return ResponHelper::handlerErrorResponJson(['error' => 'User masih memiliki pinjaman'], 400);
        }

        try {
            $this->db->transStart();

            $this->loan->deleteLoan($id_decrypt);
            $this->biodata->deleteBiodata($id_decrypt);
            $this->user->deleteUser($id_decrypt);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return ResponHelper::handlerErrorResponJson('Database transaction failed', 500);
            }

            return ResponHelper::handlerSuccessResponJson(['message' => 'User deleted successfully'], 200);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return ResponHelper::handlerErrorResponJson($e->getMessage(), 500);
        }
    }


    /**
     * Fungsi untuk mendapatkan aturan validasi inputan.
     * 
     * @return array Aturan validasi inputan.
     */
    private function getValidationRules($is_update = false)
    {
        return [
            'fullname' => [
                'rules' => $is_update ? 'max_length[100]'
                    : 'required|max_length[100]',
                'errors' => [
                    'required' => 'Nama wajib diisi.',
                    'max_length' => 'Nama maksimal 100 huruf.',
                ],
            ],
            'identification' => [
                'rules' => $is_update ? 'max_length[20]'
                    : 'required|max_length[20]|numeric',
                'errors' => [
                    'required' => 'Nomor identitas wajib diisi.',
                    'max_length' => 'Identitas tidak boleh lebih dari 20 karakter.',
                    'numeric' => 'Identitas hanya boleh berisi angka.',
                ],
            ],
            'address' => [
                'rules' => $is_update ? 'regex_match[/^[a-zA-Z0-9\s,.\-]+$/]|min_length[5]|max_length[255]'
                    : 'required|regex_match[/^[a-zA-Z0-9\s,.\-]+$/]|min_length[5]|max_length[255]',
                'errors' => [
                    'required' => 'Alamat wajib diisi.',
                    'regex_match' => 'Alamat mengandung karakter yang tidak valid.',
                    'min_length' => 'Alamat minimal harus terdiri dari 5 karakter.',
                    'max_length' => 'Alamat tidak boleh lebih dari 255 karakter.',
                ],
            ],
            'phone' => [
                'rules' => $is_update ? 'regex_match[/^62[0-9]{11,12}$/]'
                    :  'required|regex_match[/^62[0-9]{11,12}$/]',
                'errors' => [
                    'required' => 'Nomor telepon wajib diisi.',
                    'regex_match' => 'Nomor telepon harus dimulai dengan 62 dan diikuti 11 hingga 12 digit angka.',
                ],
            ],
            'gender' => [
                'rules' => $is_update ?? 'required',
                'errors' => [
                    'required' => 'Jenis kelamin wajib dipilih.',
                ],
            ],
            'religion' => [
                'rules' => $is_update ?? 'required',
                'errors' => [
                    'required' => 'Agama wajib dipilih.',
                ],
            ],
            'place_birth' => [
                'rules' => $is_update ? 'min_length[3]|max_length[50]'
                    : 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'Tempat lahir wajib diisi.',
                    'min_length' => 'Tempat lahir minimal harus terdiri dari 3 karakter.',
                    'max_length' => 'Tempat lahir tidak boleh lebih dari 50 karakter.',
                ],
            ],
            'date_birth' => [
                'rules' => $is_update ?? 'required',
                'errors' => [
                    'required' => 'Tanggal lahir wajib diisi.',
                ],
            ],
            'class_name' => [
                'rules' => $is_update ?? 'required',
                'errors' => [
                    'required' => 'Kelas wajib dipilih.',
                ],
            ],
            'email' => [
                'rules' => 'permit_empty|valid_email|emailUsernameValidation[username,email]',
                'errors' => [
                    'valid_email' => 'Masukkan alamat email yang valid.',
                    'emailUsernameValidation' => 'Username atau email wajib diisi, minimal salah satu.',
                ],
            ],
            'username_email' => [
                'rules' => $is_update ? 'min_length[5]|max_length[255]|valid_email_or_username'
                    : 'required|min_length[5]|max_length[255]|valid_email_or_username',
                'errors' => [
                    'min_length' => 'Username minimal harus terdiri dari 5 karakter.',
                    'max_length' => 'Username tidak boleh lebih dari 255 karakter.',
                    'valid_email_or_username' => 'Username atau email harus valid.'
                ],
            ],
            'password' => [
                'rules' => $is_update ? 'regex_match[/^[a-zA-Z0-9\s,.\-]+$/]|min_length[5]|max_length[255]'
                    : 'required|regex_match[/^[a-zA-Z0-9\s,.\-]+$/]|min_length[5]|max_length[255]',
                'errors' => [
                    'required' => 'Password wajib diisi.',
                    'regex_match' => 'Password mengandung karakter yang tidak valid.',
                    'min_length' => 'Password minimal harus terdiri dari 5 karakter.',
                    'max_length' => 'Password tidak boleh lebih dari 255 karakter.',
                ],
            ],
            'password_confirm' => [
                'rules' => $is_update ? 'matches[password]' : 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi.',
                    'matches' => 'Konfirmasi password tidak cocok dengan password.',
                ],
            ],
        ];
    }


    /**
     * Mengambil email atau username dari inputan
     * 
     * Fungsi ini akan mengembalikan array yang berisi email dan username
     * Jika inputan berupa email, maka akan mengembalikan array dengan email = inputan dan username = null
     * Jika inputan berupa username, maka akan mengembalikan array dengan email = null dan username = inputan
     * 
     * @param string $username_email inputan yang akan di cek
     * @return array Berisi email dan username
     * @throws \Exception jika inputan tidak valid
     */
    private function getEmailOrUsername($username_email)
    {
        if (filter_var($username_email, FILTER_VALIDATE_EMAIL)) {
            return [$username_email, null];
        } elseif (preg_match('/^[a-zA-Z0-9_]+$/', $username_email)) {
            return [null, $username_email];
        } else {
            throw new \Exception('Input harus berupa email atau username yang valid');
        }
    }


    /**
     * Menambahkan user baru ke database
     * 
     * Fungsi ini akan menambahkan data user baru beserta biodatanya
     * Jika terjadi kesalahan baik saat menambahkan user maupun biodata ke database, maka akan membatalkan proses penambahan dan menghapus data yang baru ditambahkan 
     * 
     * @param array $data Data user yang akan dimasukkan
     * @return array Berisi ID user yang baru ditambahkan dan data yang dimasukkan
     * @throws \Exception Jika terjadi kesalahan selama proses penyimpanan
     */
    private function insertUser($data)
    {
        $username_email = $this->getEmailOrUsername($data['username_email']);
        $email = $username_email[0];
        $username = $username_email[1];

        $this->db->transBegin();

        try {
            $user_id = $this->user->insertData([
                'username' => $username,
                'email' => $email,
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ]);

            $this->biodata->insertData([
                'user_id' => $user_id,
                'fullname' => $data['fullname'],
                'identification' => $data['identification'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'place_birth' => $data['place_birth'],
                'date_birth' => $data['date_birth'],
                'gender' => $data['gender'],
                'religion' => $data['religion'],
                'birth_date' => $data['class_name'],
            ]);

            $this->db->transCommit();

            return [$user_id, $data];
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }


    /**
     * Mengupdate data user berdasarkan id yang dikirimkan
     * 
     * Fungsi ini akan mengupdate data user yang memiliki id yang sama dengan parameter
     * Fungsi ini akan mengembalikan hasil update data
     * 
     * @param string $id_user id user yang akan diperbarui
     * @param array $data_user data yang akan diperbarui
     * 
     * @return array Berisi data user yang telah diupdate
     * @throws \Exception Jika terjadi kesalahan selama proses update
     */
    private function updateUser($id_user, $data_user)
    {
        $id_decrypt = $this->decryptId($id_user);
        $existingUser = $this->user->getDataUserById($id_decrypt);

        if (!$existingUser) {
            throw new \Exception('User not found', 404);
        }

        $userData = [];
        $biodataData = [];
        $userKeys = ['username', 'email', 'password'];

        foreach ($data_user as $key => $value) {
            if (in_array($key, $userKeys)) {
                $userData[$key] = $key === 'password' ? password_hash($value, PASSWORD_DEFAULT) : $value;
            } else {
                $biodataData[$key] = $value;
            }
        }

        $this->db->transBegin();
        try {
            if (!empty($userData)) {
                $this->user->updateUser($id_decrypt, $userData);
            }

            if (!empty($biodataData)) {
                $this->biodata->updateBiodataUser($id_decrypt, $biodataData);
            }

            $this->db->transCommit();

            return [
                $this->user->getDataUserById($id_decrypt),
                $this->biodata->getBiodataUserById($id_decrypt),
            ];
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }


    /**
     * Dekrip id user
     * 
     * Fungsi ini akan mengambil id user
     * Fungsi ini akan melakukan dekripsi terhadap id user
     * Jika parameter null, maka akan mengambil id user dari session
     * 
     * @param string $id_user id user yang akan didekrip
     * @return string id user yang telah didekrip
     */
    private function decryptId($id_user = null)
    {
        if ($id_user === null) {
            $id = session('id_user');
            $id_user = $id['id'];
        }

        $decode_id = $this->encrypter->decrypt(base64_decode($id_user));
        return $decode_id;
    }
}
