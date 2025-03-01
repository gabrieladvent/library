<?php

namespace App\Controllers;

use Config\Database;
use Config\Services;
use App\Models\BooksModel;
use App\Models\LoansModel;
use App\Models\UsersModel;
use App\Controllers\BaseController;
use App\Helpers\ResponHelper;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends BaseController
{

    protected $user;
    protected $encrypter;
    protected $loans;
    protected $db;
    protected $book;
    protected $alignment;
    public function __construct()
    {
        // Buat instance dari model yang digunakan
        $this->user = new UsersModel();
        $this->loans = new LoansModel();
        $this->book = new BooksModel();
        $this->db = \Config\Database::connect();
        $this->encrypter = \Config\Services::encrypter();
        $this->alignment = new \PhpOffice\PhpSpreadsheet\Style\Alignment();
    }

    public function index()
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
        return view("Content/Laporan/laporan", $data);
    }

    public function exportReport($tipe = 'excel')
    {
        $loan_id = $this->request->getGet('loans');
        $loan_data = []; // Inisialisasi default

        if ($loan_id != null) {
            $id_decrypt = $this->decryptId($loan_id);
            $loan_data = $this->loans->getDetailLoanByIdLoan($id_decrypt);
        } else {
            $loansDate = $this->request->getGet('loans_date');
            $returnDate = $this->request->getGet('return_date');
            $status = $this->request->getGet('status');
            // Ambil data berdasarkan filter
            $loan_data = $this->loans->getLoansByFilter($loansDate, $returnDate, $status);
        }

        // Pastikan data tidak kosong
        if (empty($loan_data)) {
            return ResponHelper::handlerErrorResponRedirect('report/list', 'Data tidak ditemukan');
        }

        $this->exportExcel($tipe, $loan_data);
        return ResponHelper::handlerErrorResponRedirect('report/list', 'Data tidak ditemukan');
    }

    private function exportExcel($tipe, $data)
    {
        if (!isset($data[0])) {
            $data = [$data];
        }

        $directory = ROOTPATH . 'public/data/export/';
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $templatePath = 'data/template.xlsx';
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);


        // Merge header title
        $sheet->mergeCells('A2:M2');
        $sheet->setCellValue('A2', 'LAPORAN PEMINJAMAN PERPUSTAKAAN SEKOLAH');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal($this->alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:M3');
        $sheet->setCellValue('A3', 'SMP SWASTA KATOLIK SANTA URSULA ENDE');
        $sheet->getStyle('A3')->getFont()->setSize(14);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal($this->alignment::HORIZONTAL_CENTER);

        $row = 5;
        $sheet->setCellValue('A' . $row, 'No');
        $sheet->setCellValue('B' . $row, 'Nama Peminjam');
        $sheet->setCellValue('C' . $row, 'Kelas');
        $sheet->setCellValue('D' . $row, 'NISN');
        $sheet->setCellValue('E' . $row, 'Nama Buku');
        $sheet->setCellValue('F' . $row, 'ISBN');
        $sheet->setCellValue('G' . $row, 'Jumlah Pinjaman');
        $sheet->setCellValue('H' . $row, 'Tanggal Peminjaman');
        $sheet->setCellValue('I' . $row, 'Tanggal Pengembalian');
        $sheet->setCellValue('J' . $row, 'Status');

        $sheet->mergeCells('K' . $row . ':M' . $row);
        $sheet->setCellValue('K' . $row, 'Catatan');

        // Style untuk header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0CCE6B']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'   => true
            ]
        ];
        $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray($headerStyle);

        $row++;
        $rowNumber = 1;

        // Isi data
        foreach ($data as $loans) {
            $sheet->setCellValue('A' . $row, $rowNumber);
            $sheet->setCellValue('B' . $row, $loans['fullname']);
            $sheet->setCellValue('C' . $row, $loans['class_name']);
            $sheet->setCellValue('D' . $row, $loans['identification'] == 0 ? '-' : $loans['identification']);
            $sheet->setCellValue('E' . $row, $loans['book_name']);
            $sheet->setCellValue('F' . $row, $loans['isbn'] == 0 ? '-' : $loans['isbn']);
            $sheet->setCellValue('G' . $row, $loans['quantity']);
            $sheet->setCellValue('H' . $row, date('d-m-Y', strtotime($loans['loan_date'])));
            $sheet->setCellValue('I' . $row, date('d-m-Y', strtotime($loans['return_date_expected'])));
            $sheet->setCellValue('J' . $row, $loans['status']);
            $sheet->mergeCells('K' . $row . ':M' . $row);
            $sheet->setCellValue('K' . $row, $loans['notes']);

            $row++;
            $rowNumber++;
        }

        // Auto width hanya untuk isi, header tetap wrap text dan lebar manual
        foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Atur lebar manual untuk header agar wrap text aktif
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(15);

        // Border untuk seluruh data
        $dataRange = 'A5:M' . ($row - 1);
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        $sheet->getStyle($dataRange)->applyFromArray($borderStyle);

        // Mengunci header saat scroll
        $sheet->freezePane('A6');

        $filename = 'Laporan Peminjaman';
        if ($tipe === 'pdf') {
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Mpdf');
            $filePath = $directory . $filename . '.pdf';
            $writer->save($filePath);

            return $this->response->download($filePath, null)->setFileName($filename . '.pdf');
        } else {
            $writer = new Xlsx($spreadsheet);
            $filePath = $directory . $filename . '.xlsx';
            $writer->save($filePath);

            return $this->response->download($filePath, null)->setFileName($filename . '.xlsx');
        }
    }

    private function decryptId($id_book)
    {
        $decode_id = $this->encrypter->decrypt(base64_decode($id_book));
        return $decode_id;
    }
}
