<?php

namespace App\Controllers;

use App\Models\BooksModel;
use App\Models\LoansModel;
use App\Models\UsersModel;
use App\Helpers\ResponHelper;
use App\Controllers\BaseController;
use App\Models\BiodataUsersModel;
use CodeIgniter\HTTP\ResponseInterface;

class LoansController extends BaseController
{


    protected $user;
    protected $encrypter;
    protected $loans;
    protected $biodata;
    protected $db;
    protected $book;
    public function __construct()
    {
        // Buat instance dari model yang digunakan
        $this->user = new UsersModel();
        $this->loans = new LoansModel();
        $this->biodata = new BiodataUsersModel();
        $this->book = new BooksModel();
        $this->db = \Config\Database::connect();
        $this->encrypter = \Config\Services::encrypter();
    }


    public function viewLoans()
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


        $data['user'] = $this->user->getDataUserById($decode_id);
        $data['loans'] = $this->loans->getAllLoans();
        // dd($data);
        return view("Content/PeminjamanBuku/PinjamBuku", $data);
    }

    public function viewDetailLoans()
    {
        $id_loans = $_GET['loans'];
        if (empty($id_loans)) {
            return ResponHelper::handlerErrorResponJson('ID invalid.', 400);
        }
        $id_decrypt = $this->decryptId($id_loans);

        $loan = $this->loans->getDetailLoanByIdLoan($id_decrypt);
        if ($loan === null) {
            return ResponHelper::handlerErrorResponJson('Data not found.', 404);
        }

        $data = [
            'loan' => $loan,
            'book' => $this->book->getDataById($loan['book_id']),
            'user' => $this->user->getUserById($loan['user_id'])
        ];
        log_message("info", "data detail loans" . json_encode($data));

        return ResponHelper::handlerSuccessResponJson('success', 200, $data);
    }

    public function addLoans()
    {
        $id_user = session('id_user');
        if (!$id_user || !isset($id_user['id'])) {
            return ResponHelper::handlerErrorResponRedirect('loans/list', 'Data tidak valid. id user');
        }

        $decode_id = $this->encrypter->decrypt(base64_decode($id_user['id']));
        if ($this->user->getDataUserById($decode_id) == null) {
            return ResponHelper::handlerErrorResponRedirect('loans/list', 'Data tidak valid. tdk dapat id');
        }

        $data_loans = $this->request->getPost();
        $data_loans['pelayan_id'] = $decode_id;
        $quantity = $data_loans['quantity'];
        $data_available = $data_loans['available_books'] - $quantity;
        unset($data_loans['available_books']);

        try {
            $this->db->transStart();

            $this->loans->insert($data_loans);
            $this->book->update($data_loans['book_id'], ['available_books' => $data_available]);

            $this->db->transComplete();
            return ResponHelper::handlerSuccessResponRedirect('loans/list', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return ResponHelper::handlerErrorResponRedirect('loans/list', 'Data tidak valid: ' . $th->getMessage());
        }
    }

    public function editLoans()
    {
        // dd($this->request->getPost());
        $this->db->transStart();
        try {
            $id_loans = $this->request->getGet('loans');
            if (empty($id_loans)) {
                return ResponHelper::handlerErrorResponRedirect('loans/list', 'ID invalid.');
            }

            $id_decrypt = $this->decryptId($id_loans);
            $detail_data = $this->loans->getDetailLoanByIdLoan($id_decrypt);
            if (!$detail_data) {
                return ResponHelper::handlerErrorResponRedirect('loans/list', 'Data tidak ditemukan');
            }

            $data_loans = $this->request->getPost();
            if (empty($data_loans)) {
                return ResponHelper::handlerErrorResponRedirect('loans/list', 'Data tidak ditemukan');
            }

            $updateData = [];

            // Handle status change
            if (isset($data_loans['status']) && $data_loans['status'] !== $detail_data['status']) {
                switch ($data_loans['status']) {
                    case 'Dikembalikan':
                        $data_book = $this->book->getDataById($data_loans['book_id']);
                        if ($data_book) {
                            $data_book['available_books'] += $detail_data['quantity'];
                            $this->book->update($data_loans['book_id'], $data_book);
                        }
                        $updateData['status'] = 'Dikembalikan';
                        break;

                    case 'Diperpanjang':
                        if ($data_loans['return_date_expected'] === $detail_data['return_date_expected']) {
                            return ResponHelper::handlerErrorResponRedirect('loans/list', 'Tanggal Pengembalian Sama');
                        }
                        $updateData['return_date_expected'] = $data_loans['return_date_expected'];
                        $updateData['status'] = 'Diperpanjang';
                        break;
                }
            }

            if (isset($data_loans['quantity']) && $data_loans['quantity'] !== $detail_data['quantity']) {
                $quantity_diff = $data_loans['quantity'] - $detail_data['quantity'];
                $data_book = $this->book->getDataById($data_loans['book_id']);
                if ($data_book) {
                    $data_book['available_books'] -= $quantity_diff;
                    if ($data_book['available_books'] < 0) {
                        throw new \Exception('Transaction failed.');
                    }
                    $this->book->update($data_loans['book_id'], $data_book);
                }
                $updateData['quantity'] = $data_loans['quantity'];
            }

            if (!empty($updateData)) {
                $this->loans->update($id_decrypt, $updateData);
            }

            $this->db->transComplete();
            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaction failed.');
            }

            return ResponHelper::handlerSuccessResponRedirect('loans/list', 'Berhasil mengupdate pinjaman.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return ResponHelper::handlerErrorResponRedirect('loans/list', 'Gagal mengupdate peminjaman');
        }
    }



    public function deleteLoans()
    {
        $this->db->transStart();
        try {
            $id_loans = $this->request->getGet('loans');
            if (empty($id_loans)) {
                return ResponHelper::handlerErrorResponJson('ID invalid.', 400);
            }

            $id_decrypt = $this->decryptId($id_loans);
            $detail_data = $this->loans->getDetailLoanByIdLoan($id_decrypt);
            if (!$detail_data) {
                return ResponHelper::handlerErrorResponJson('Data not found.', 404);
            }

            $data_book = $this->book->getDataById($detail_data['book_id']);
            if ($data_book) {
                $data_book['available_books'] += $detail_data['quantity'];
                $this->book->update($detail_data['book_id'], $data_book);
            }

            $this->loans->delete($id_decrypt);

            $this->db->transComplete();
            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaction failed.');
            }

            return ResponHelper::handlerSuccessResponJson('Loan deleted successfully.', 200);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return ResponHelper::handlerErrorResponJson($e->getMessage(), 500);
        }
    }

    private function decryptId($id_book)
    {
        $decode_id = $this->encrypter->decrypt(base64_decode($id_book));
        return $decode_id;
    }
}
