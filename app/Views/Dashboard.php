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
                        <h5>Diagram Tambahan</h5>
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
</div>
</div>

<!-- CSS tambahan -->


<!-- Script untuk grafik -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('loanChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: [5, 8, 6, 9, 7, 4, 6], // Data Peminjaman
                        borderColor: '#FFA500',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Jumlah Pengembalian',
                        data: [4, 2, 5, 1, 6, 3, 5], // Data Pengembalian
                        borderColor: '#32CD32',
                        backgroundColor: 'rgba(33, 150, 243, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Jumlah Terlambat',
                        data: [1, 2, 1, 5, 2, 3, 2], // Data Terlambat
                        borderColor: '#FF4500',
                        backgroundColor: 'rgba(255, 152, 0, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true, // Supaya fleksibel
                aspectRatio: 5, // Atur rasio panjang terhadap tinggi
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
    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCategory, {
        type: 'pie',
        data: {
            labels: ['Fiksi', 'Non-Fiksi', 'Teknologi', 'Sains', 'Sejarah'],
            datasets: [{
                label: 'Persentase Kategori Buku Dipinjam',
                data: [25, 20, 30, 15, 10],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#FF9800'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                },
                datalabels: { // Tambahkan plugin data labels
                    formatter: (value, ctx) => {
                        let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        let percentage = (value / sum * 100).toFixed(1) + "%"; // Hitung persentase
                        return percentage;
                    },
                    color: '#fff', // Warna teks
                    font: {
                        weight: 'bold',
                        size: 14 // Ukuran teks
                    }
                }
            }
        },
        plugins: [ChartDataLabels] // Aktifkan plugin
    });
</script>


<script>
    // Data untuk diagram tambahan (Bar Chart)
    const extraData = {
        labels: ['A', 'B', 'C', 'D', 'E', 'F', 'G'], // 7 Label
        datasets: [{
            label: 'Data Tambahan',
            data: [10, 15, 7, 20, 12, 18, 25], // 7 Data sesuai label
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#FF9800', '#9C27B0', '#3F51B5'], // Warna lebih banyak
            borderWidth: 1
        }]
    };


    // Render Diagram Tambahan
    const ctxExtra = document.getElementById('extraChart').getContext('2d');
    new Chart(ctxExtra, {
        type: 'bar',
        data: extraData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>