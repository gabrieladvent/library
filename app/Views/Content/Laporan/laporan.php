<?= $this->extend('Layouts/default') ?>

<?php $this->section('content');
$encrypter = \Config\Services::encrypter();
?>

<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        width: 300px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .close {
        float: right;
        font-size: 24px;
        cursor: pointer;
    }

    .btn-excel {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        margin: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-pdf {
        background-color: #dc3545;
        color: white;
        padding: 10px 20px;
        margin: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-excel:hover {
        background-color: #218838;
    }

    .btn-pdf:hover {
        background-color: #c82333;
    }
</style>

<div class="container-book">
    <div class="container-buku">
        <div class="head">
            <div class="title">
                <h1>Laporan</h1>
                <a class="reset" href="" onclick="resetFilters();">
                    <i class='bx bx-reset'></i>
                </a>
            </div>
        </div>
        <div class="print_container">
            <button type="button" onclick="openPrintModal()" class="printAll_container">
                <i class='bx bxs-printer'></i>
                <p>Print All</p>
            </button>

            <div class="search">
                <label for="search_loansdate">Pilih Tanggal Peminjaman</label>
                <input id="search_loansdate" type="date">
            </div>
            <div class="search">
                <label for="search_returndate">Pilih Tanggal Pengembalian</label>
                <input id="search_returndate" type="date">
            </div>
            <div class="search">
                <label for="search_status">Status</label>
                <select id="search_status">
                    <option value="">Semua Peminjaman</option>
                    <option value="Dipinjam">Dipinjam</option>
                    <option value="Dikembalikan">Dikembalikan</option>
                    <option value="Diperpanjang">Diperpanjang</option>
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
                            <th>Tanggal Pengembalian</th>
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
                                    <td><?= $loan['fullname'] ?></td>
                                    <td><?= $loan['book_name'] ?></td>
                                    <td><?= $loan['loan_date'] ?></td>
                                    <td><?= $loan['return_date_expected'] ?></td>
                                    <td><?= $loan['quantity'] ?></td>
                                    <td><?= $loan['status'] ?></td>
                                    <td>
                                        <div class="button_print">
                                            <button onclick="openPrintModal(this)"
                                                data-loan-id="<?= base64_encode($encrypter->encrypt($loan['loan_id'])) ?>"
                                                class="btn btn-print">
                                                <i id="print" class='bx bxs-printer'></i> Print
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">Data Tidak Ditemukan</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="printModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closePrintModal()">&times;</span>
        <h2>Pilih Format Cetak</h2>
        <p>Silakan pilih format laporan yang ingin dicetak:</p>
        <button class="btn btn-excel" onclick="printWithFilters('excel')">Excel</button>
        <button class="btn btn-pdf" onclick="printWithFilters('pdf')">PDF</button>
    </div>
</div>


<script>
    let selectedLoanId = null;

    function openPrintModal(button = null) {
        selectedLoanId = button ? button.getAttribute("data-loan-id") : null;
        document.getElementById("printModal").style.display = "flex";
    }

    function closePrintModal() {
        document.getElementById("printModal").style.display = "none";
    }

    function printWithFilters(format) {
        let loansDate = document.getElementById('search_loansdate').value;
        let returnDate = document.getElementById('search_returndate').value;
        let status = document.getElementById('search_status').value;
        let url = "<?= base_url('report/print/') ?>" + format;

        if (selectedLoanId) {
            url += "?loans=" + encodeURIComponent(selectedLoanId);
        } else {
            url += "?loans_date=" + encodeURIComponent(loansDate);
            url += "&return_date=" + encodeURIComponent(returnDate);
            url += "&status=" + encodeURIComponent(status);
        }

        console.log("Opening URL:", url);

        // Validasi sebelum membuka tab baru
        fetch(url, {
                method: 'HEAD'
            }) // Cek apakah URL valid
            .then(response => {
                if (!response.ok) {
                    throw new Error("Gagal memuat laporan. Pastikan data tersedia.");
                }
                window.open(url, '_blank');
            })
            .catch(error => {
                Toastify({
                    className: "notif bx bxs-x-circle",
                    text: error.message, // Menggunakan pesan error dari JavaScript
                    duration: 2000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#FFD9E7",
                    style: {
                        marginTop: "60px",
                        color: "red",
                        borderRadius: "8px",
                    },
                }).showToast();
            });

        closePrintModal();
    }


    window.onclick = function(event) {
        let modal = document.getElementById("printModal");
        if (event.target === modal) {
            closePrintModal();
        }
    };

    function resetFilters() {
        document.getElementById('search_loansdate').value = "";
        document.getElementById('search_returndate').value = "";
        document.getElementById('search_status').value = "";
    }

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