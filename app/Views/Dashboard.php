<?= $this->extend('Layouts/default') ?>

<?= $this->section('content') ?>
<div class="dashboard_container">
    <div class="top_dashboard_container">
        <!-- Bagian atas (yang sudah ada) -->
        <div class="view-container" id="loans-container">
            <h1><?= $count_newborrower ?></h1>
            <p>Peminjaman Baru</p>
            <div class="container_img">
                <img src="<?= base_url("img/Peminjaman.png") ?>" alt="" />
            </div>
        </div>
        <div class="view-container" id="book-container">
            <h1><?= $count_book ?></h1>
            <p>Buku Tersedia</p>
            <div class="container_img">
                <img src="<?= base_url("img/Buku.png") ?>" alt="" />
            </div>
        </div>
        <div class="view-container" id="users-container">
            <h1><?= $count_users['count_user'] ?></h1>
            <p>jumlah Anggota</p>
            <div class="container_img">
                <img src="<?= base_url("img/Anggota.png") ?>" alt="" />
            </div>
        </div>

    </div>

    <div class="button_dashboard_container">
        <!-- Bagian bawah (Statistik dan Aktivitas) -->
        <div class="statistics-container">
            <!-- Grafik Peminjaman -->
            <div class="chart-container">
                <div class="card">
                    <div class="card-header">
                        <h5>Statistik Peminjaman Mingguan</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="loanChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Buku Terpopuler -->
            <div class="popular-books">
                <div class="card">
                    <div class="card-header">
                        <h5>Buku Paling Sering Dipinjam</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Judul Buku</th>
                                        <th>Total Dipinjam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data dummy, sesuaikan dengan data real -->
                                    <tr>
                                        <td>Matematika Kelas 7</td>
                                        <td>15 kali</td>
                                    </tr>
                                    <tr>
                                        <td>IPA Kelas 7</td>
                                        <td>12 kali</td>
                                    </tr>
                                    <tr>
                                        <td>Bahasa Indonesia Kelas 7</td>
                                        <td>10 kali</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aktivitas Terbaru -->

    </div>
</div>

<!-- CSS tambahan -->


<!-- Script untuk grafik -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('loanChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: [5, 8, 6, 9, 7, 4, 6],
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>

<?= $this->endSection() ?>