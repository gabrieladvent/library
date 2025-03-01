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
        </div>

        <!-- Tambahan Diagram di Bawah -->
        <div class="chart_buttom">
            <div class="extra_chart">
                <div class="card">
                    <div class="card-header">
                        <h5>Peminjaman Per Kelas</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="extraChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="chart_pie">
                <div class="card">
                    <div class="card-header">
                        <h5>Distribusi Kategori Buku Dipinjam</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- CSS tambahan -->


<!-- Script untuk grafik -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('loanChart').getContext('2d');

        // Ambil data dari PHP
        const labels = <?= $labels_line ?>;
        const loanData = <?= $loan_data_line ?>;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Dipinjam',
                        data: loanData.Dipinjam,
                        borderColor: '#FFA500',
                        tension: 0.4,
                        fill: false
                    },
                    {
                        label: 'Diperpanjang',
                        data: loanData.Diperpanjang,
                        borderColor: '#32CD32',
                        tension: 0.4,
                        fill: false
                    },
                    {
                        label: 'Dikembalikan',
                        data: loanData.Dikembalikan,
                        borderColor: '#1E90FF',
                        tension: 0.4,
                        fill: false
                    },
                    {
                        label: 'Terlambat',
                        data: loanData.Terlambat,
                        borderColor: '#FF4500',
                        tension: 0.4,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctxCategory = document.getElementById('categoryChart').getContext('2d');

        // Ambil data dari PHP
        const labelsCategory = <?= $labels_pie ?>;
        const dataCategory = <?= $loan_data_pie ?>;

        new Chart(ctxCategory, {
            type: 'pie',
            data: {
                labels: labelsCategory, // Nama kategori buku
                datasets: [{
                    label: 'Jumlah Buku per Kategori',
                    data: dataCategory,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#FF9800', '#9C27B0', '#3F51B5'], 
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctxExtra = document.getElementById('extraChart').getContext('2d');

        // Ambil data dari PHP
        const labelsClass = <?= $labels_bar ?>;
        const dataClass = <?= $loan_data_bar ?>;

        new Chart(ctxExtra, {
            type: 'bar',    
            data: {
                labels: labelsClass, // Nama kelas
                datasets: [{
                    label: labelsClass,
                    data: dataClass,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#FF9800', '#9C27B0', '#3F51B5'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: false,
                    }
                }
            }
        });
    });
</script>


<?= $this->endSection() ?>