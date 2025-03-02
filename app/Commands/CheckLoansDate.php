<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class CheckLoansDate extends BaseCommand
{
    protected $group       = 'custom';
    protected $name        = 'checkLoans';
    protected $description = 'Untuk mengecek dan mengubah status dari peminjaman';

    /**
     * Command untuk mengecek dan mengubah status dari peminjaman.
     * Hanya akan mengubah status menjadi 'Terlambat' jika tanggal
     * pengembalian telah melebihi tanggal sekarang.
     */
    public function run(array $params)
    {
        $db = Database::connect();

        $query = $db->table('loans')
            ->where('status !=', 'Terlambat')
            ->get();

        $loans = $query->getResult();

        if (empty($loans)) {
            CLI::write('Tidak ada data yang perlu diperbarui.', 'yellow');
            return;
        }

        $now = date('Y-m-d');
        $updated = 0;

        foreach ($loans as $loan) {
            // Jika tanggal sekarang lebih besar dari tanggal pengembalian
            // yang diharapkan maka status akan diupdate menjadi 'Terlambat'
            if ($now > $loan->return_date_expected) {
                $db->table('loans')
                    ->where('id', $loan->id)
                    ->update(['status' => 'Terlambat']);
                $updated++;
                CLI::write("Loan ID {$loan->id} diperbarui menjadi 'Terlambat'", 'red');
            }
        }

        CLI::write("Total data diperbarui: $updated", 'green');
    }
}
