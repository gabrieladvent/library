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
                    <option value="pinjam">Pinjam</option>
                    <option value="dikembalikan">Dikembalikan</option>
                    <option value="perpanjang">Perpanjang</option>
                    <option value="terlambat">Terlambat</option>
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
                            <th>Tanggal Pengembalian</th>
                            <th>Jumlah Buku</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="dataTable">
                        <!-- Contoh data. Pastikan format tanggal sesuai dengan nilai input (YYYY-MM-DD) -->
                        <tr>
                            <td>1</td>
                            <td>Kelvin</td>
                            <td>Dilan</td>
                            <td>2025-01-01</td>
                            <td>2025-01-05</td>
                            <td>1 Buku</td>
                            <td>dikembalikan</td>
                            <td>
                                <div class="button_print">
                                    <button onclick="window.open('<?= site_url('laporan/printPDF/1') ?>', '_blank');" class="btn btn-print">
                                        <i id="print" class='bx bxs-printer'></i> Print
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Andi</td>
                            <td>Rembulan</td>
                            <td>2025-01-02</td>
                            <td>2025-01-06</td>
                            <td>2 Buku</td>
                            <td>pinjam</td>
                            <td>
                                <div class="button_print">
                                    <button onclick="window.open('<?= site_url('laporan/printPDF/2') ?>', '_blank');" class="btn btn-print">
                                        <i id="print" class='bx bxs-printer'></i> Print
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Sinta</td>
                            <td>Bulan</td>
                            <td>2025-01-03</td>
                            <td>2025-01-07</td>
                            <td>1 Buku</td>
                            <td>terlambat</td>
                            <td>
                                <div class="button_print">
                                    <button onclick="window.open('<?= site_url('laporan/printPDF/3') ?>', '_blank');" class="btn btn-print">
                                        <i id="print" class='bx bxs-printer'></i> Print
                                    </button>
                                </div>
                            </td>
                        </tr>
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

        function filterTable() {
            const loansDate = loansInput.value; // Format: YYYY-MM-DD
            const returnDate = returnInput.value; // Format: YYYY-MM-DD
            const statusValue = statusSelect.value.toLowerCase().trim();

            // Dapatkan semua baris data
            const rows = tableBody.querySelectorAll("tr");

            rows.forEach(row => {
                // Ambil data dari setiap kolom (index disesuaikan dengan posisi kolom)
                const cells = row.querySelectorAll("td");
                const rowLoansDate = cells[3].textContent.trim();
                const rowReturnDate = cells[4].textContent.trim();
                const rowStatus = cells[6].textContent.trim().toLowerCase();

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