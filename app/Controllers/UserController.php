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
        $data['class'] = $this->class->getAllClasses();
        $data['type'] = $type;

        if (!$data['user']) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan');
        }

        $view = $type === 'Admin' ? 'content/Admin/admin' : 'content/MasterData/anggota';

        // dd($data);
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

        try {
            $data = [
                'success' => true,
                'data' => [
                    'user_detail' => $this->user->getDetailUserById($id_decrypt),
                    'user_loans' => $this->loan->getLoanByIdUser($id_decrypt)
                ]
            ];

            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
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
        try {
            // $valid = \Config\Services::validation();
            // $validate = $this->getValidationRules();

            // dd(!$valid->setRules($validate)->run($this->request->getPost()));

            // if (!$valid->setRules($validate)->run($this->request->getPost())) {
            //     $message = $valid->getErrors();
            //     return ResponHelper::handlerErrorResponRedirect('registrasi/employee', $message);
            // }

            // Mendapatkan data input
            $data_user = $this->request->getPost();

            // Pastikan data input berupa array
            if (!$data_user || !is_array($data_user)) {
                return ResponHelper::handlerErrorResponJson(['error' => 'Data input tidak valid'], 400);
            }

            // Menyimpan data ke database$insert_user = $this->insertUser($data_user);
            $insert_user = $this->insertUser($data_user);

            // Mengembalikan respons sukses
            return ResponHelper::handlerSuccessResponJson(
                ['user' => $insert_user[0], 'biodata' => $insert_user[1]],
                201
            );
        } catch (\Exception $e) {
            // Menangani error dan mengembalikan pesan error
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
    public function editUser()
    {
        $data_user = $this->request->getPost();
        $id_user = $_GET['users'] ?? null;
        if (empty($data_user) || !$data_user) {
            log_message('error', 'Request data is empty.');
            return ResponHelper::handlerErrorResponJson('Data tidak valid.', 400);
        }

        // $validationRules = $this->getValidationRules(true);
        // if (empty($validationRules)) {
        //     log_message('error', 'Validation rules are empty.');
        //     return ResponHelper::handlerErrorResponJson('Kesalahan server internal.', 500);
        // }

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


    public function deleteUser()
    {
        $id_user = $_GET['users'] ?? null;
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
     * Menampilkan halaman yang berisi list kelas
     * 
     * Fungsi ini akan menampilkan halaman yang berisi list kelas
     * Fungsi ini akan mengirimkan data kelas ke view
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getAllClasses()
    {
        $all_classes = $this->class->getAllClasses(); // Ambil semua data kelas

        if (empty($all_classes)) {
            // Log jika data kosong
            log_message('error', 'Data all_classes kosong.');
        } else {
            // Debug data jika terisi
            log_message('info', 'Data all_classes: ' . json_encode($all_classes));
        }

        $data['all_classes'] = $all_classes; // Kirim data ke view
        return view('content/MasterData/kelas', $data);
    }


    /**
     * Menambahkan kelas baru ke database
     * 
     * Fungsi ini akan menambahkan data kelas baru ke database
     * Jika kelas sudah ada, maka akan mengembalikan respon error 400
     * Jika kelas berhasil ditambahkan, maka akan mengembalikan respon success 201
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function addClass()
    {
        $data_classs = $this->request->getPost();
        $isExists = $this->class->checkName($data_classs['class_name']);

        if ($isExists) {
            return ResponHelper::handlerErrorResponJson('Kelas sudah ada', 400);
        }

        try {
            $this->class->insert($data_classs);
            return ResponHelper::handlerSuccessResponJson($data_classs, 201);
        } catch (\Throwable $th) {
            return ResponHelper::handlerErrorResponJson([$th->getMessage(), $th->getTraceAsString()], 400);
        }
    }


    /**
     * Menghapus kelas berdasarkan id kelas yang dikirimkan
     * 
     * Fungsi ini akan menghapus kelas yang memiliki id kelas yang sama dengan parameter
     * Fungsi ini akan mengembalikan respon dalam format json
     * Jika data yang dikirimkan tidak valid, maka akan mengembalikan respon error 400
     * Jika data yang dikirimkan valid, maka akan mengembalikan respon success 200
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function deleteClass()
    {
        $id_class = $_GET['class'] ?? null;
        $id_decrypt = $this->decryptId($id_class);

        $check_user = $this->biodata->getDataByClassId($id_decrypt);
        if (!empty($check_user)) {
            return ResponHelper::handlerErrorResponJson(['error' => 'Class still has users'], 400);
        }

        try {
            $this->db->transStart();
            $this->biodata->deleteBiodata($id_decrypt);
            $this->class->delete($id_decrypt);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return ResponHelper::handlerErrorResponJson('Database transaction failed', 500);
            }

            return ResponHelper::handlerSuccessResponJson(['message' => 'class deleted successfully'], 200);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return ResponHelper::handlerErrorResponJson($e->getMessage(), 500);
        }
    }


    /**
     * Fungsi untuk mengedit data kelas berdasarkan id kelas yang dikirimkan
     * 
     * Fungsi ini akan mengedit data kelas yang memiliki id kelas yang sama dengan parameter
     * Fungsi ini akan mengembalikan respon dalam format json
     * Jika data yang dikirimkan tidak valid, maka akan mengembalikan respon error 400
     * Jika data yang dikirimkan valid, maka akan mengembalikan respon success 200
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function editClass()
    {
        $id_class = $_GET['class'] ?? null;
        $id_decrypt = $this->decryptId($id_class);
        $class = $this->class->find($id_decrypt);

        if (empty($class)) {
            return ResponHelper::handlerErrorResponJson(['error' => 'Class not found'], 404);
        }

        $data_classs = $this->request->getPost();
        $isExist = $this->class->checkName($data_classs['class_name']);

        if ($isExist) {
            return ResponHelper::handlerErrorResponJson(['error' => 'Nama kelas sudah ada'], 400);
        }

        $this->class->update($id_decrypt, $data_classs);
        return ResponHelper::handlerSuccessResponJson($data_classs, 200);
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
                'password' => !empty($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null,
                'role' => empty($data['role']) ? 'User' : 'Admin',
            ]);

            // dd($user_id, $data);

            $data_biodata = $this->biodata->insertData([
                'user_id' => $user_id['id'],
                'fullname' => $data['fullname'],
                'identification' => $data['identification'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'place_birth' => $data['place_birth'],
                'date_birth' => $data['date_birth'],
                'gender' => $data['gender'],
                'religion' => $data['religion'],
                'class_id' => $user_id['role'] === 'Admin' ? null : $data['class_name'],
            ]);

            if ($data_biodata == false) {
                $this->db->transRollback();
                throw new \Exception('Failed to insert biodata');
            }

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

        try {
            $decode_id = $this->encrypter->decrypt(base64_decode($id_user));
            return $decode_id;
        } catch (\Exception $e) {
            throw new \Exception('Dekripsi ID gagal: ' . $e->getMessage());
        }
    }
}
