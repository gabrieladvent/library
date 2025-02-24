<?= $this->extend('Layouts/default') ?>

<?php $this->section('content');
$encrypter = \Config\Services::encrypter();
?>

<div class="container-book">
    <div class="container-buku">
        <div class="head">
            <div class="title">
                <h1>Laporan</h1>
                <a class="reset" href="">
                    <i class='bx bx-reset'></i>
                </a>
            </div>
        </div>
        <div class="print_container">
            <button type="button" onclick="window.open('<?= site_url('laporan/printAll') ?>', '_blank');" class="printAll_container">
                <i class='bx bxs-printer'></i>
                <p>Print All</p>
            </button>

            <div class="search">
                <label for="search_loansdate">Pilih Tanggal Peminjaman</label>
                <input id="search_loansdate" type="date">
            </div>
            <div class="search">
                <label for="search_returndate">Pilih Tanggal Pengembalian</label>
                <input id="search_returndate" type="date" placeholder="pilih tanggal pengembalian">
            </div>
            <div class="search">
                <label for="search_status">Status</label>
                <select id="search_status">
                    <option value="">Pilih Status</option>
                    <option value="Dipinjam">Dipinjam</option>
                    <option value="Dikembalikan">Dikembalikan</option>
                    <option value="Perpanjang">Perpanjang</option>
                    <option value="Terlambat">Terlambat</option>
                </select>
            </div>

        </div>
        <div class="container-table">
            <div class="table">
                <table border="1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Anggota</th>
                            <th>Nama Buku</th>
                            <th>Tanggal Peminjaman</th>
                            <th>Tanggal Pengembalian (Ekspektasi)</th>
                            <th>Tanggal Pengembalian (Aktual)</th>
                            <th>Jumlah Buku</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="dataTable">
                        <?php if (!empty($loans)): ?>
                            <?php foreach ($loans as $index => $loan): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($loan['fullname']) ?></td>
                                    <td><?= htmlspecialchars($loan['book_name']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($loan['loan_date'])) ?></td>
                                    <td><?= date('d-m-Y', strtotime($loan['return_date_expected'])) ?></td>
                                    <td>
                                        <?= $loan['status'] == 'Dikembalikan' ? date('d-m-Y', strtotime($loan['return_date_actual'])) : '-' ?>
                                    </td>
                                    <td><?= (int) $loan['quantity'] ?></td>
                                    <td><?= htmlspecialchars($loan['status']) ?></td>
                                    <td>
                                        <div class="button_print">
                                            <button onclick="window.open('<?= site_url('laporan/printPDF/' . $loan['id']) ?>', '_blank');" class="btn btn-print">
                                                <i id="print" class='bx bxs-printer'></i> Print
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">Data tidak ditemukan</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<script>
    // Simulasi filtering data pada tabel
    document.addEventListener("DOMContentLoaded", function() {
        const loansInput = document.getElementById("search_loansdate");
        const returnInput = document.getElementById("search_returndate");
        const statusSelect = document.getElementById("search_status");
        const tableBody = document.getElementById("dataTable");

        function formatDate(dateString) {
            const [year, month, day] = dateString.split("-");
            return `${day}-${month}-${year}`;
        }

        function filterTable() {
            const loansDate = formatDate(loansInput.value);
            const returnDate = formatDate(returnInput.value);
            const statusValue = statusSelect.value.toLowerCase().trim();

            // Dapatkan semua baris data
            const rows = tableBody.querySelectorAll("tr");

            rows.forEach(row => {
                // Ambil data dari setiap kolom (index disesuaikan dengan posisi kolom)
                const cells = row.querySelectorAll("td");
                const rowLoansDate = cells[3].textContent.trim();
                const rowReturnDate = cells[4].textContent.trim();
                const rowStatus = cells[7].textContent.trim().toLowerCase();

                let showRow = true;

                // Filter berdasarkan Tanggal Peminjaman jika input diisi
                if (loansDate && rowLoansDate !== loansDate) {
                    showRow = false;
                }

                // Filter berdasarkan Tanggal Pengembalian jika input diisi
                if (returnDate && rowReturnDate !== returnDate) {
                    showRow = false;
                }

                // Filter berdasarkan Status jika input diisi
                if (statusValue && rowStatus !== statusValue) {
                    showRow = false;
                } else{
                    // Jika status kosong, tampilkan semua baris
                    showRow = true;
                }

                // Tampilkan atau sembunyikan baris
                row.style.display = showRow ? "" : "none";
            });
        }

        // Tambahkan event listener untuk setiap input/select
        loansInput.addEventListener("change", filterTable);
        returnInput.addEventListener("change", filterTable);
        statusSelect.addEventListener("change", filterTable);
    });
</script>

<?= $this->endSection() ?>