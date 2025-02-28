<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?= $this->extend('Layouts/default') ?>

    <?= $this->section('content') ?>
    <h1>Hjdfsfsfdsfsdfsd!</h1>
    <?= $this->endSection() ?>

</body>

</html>

<!--  -->
<div class="chart-container" style="max-width: 400px;">
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

<!-- Tambahan Diagram di Bawah -->
<div class="extra-chart-container" style="max-width: 500px; margin-top: 20px;">
    <div class="card">
        <div class="card-header">
            <h5>Diagram Tambahan</h5>
        </div>
        <div class="card-body">
            <canvas id="extraChart"></canvas>
        </div>
    </div>
</div>
</div>
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data manual sementara untuk pengujian
        const categoryData = {
            labels: ['Fiksi', 'Non-Fiksi', 'Teknologi', 'Sains', 'Sejarah'],
            datasets: [{
                label: 'Persentase Kategori Buku Dipinjam',
                data: [25, 20, 30, 15, 10],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#FF9800'],
                borderWidth: 1
            }]
        };

        // Render Pie Chart
        const ctxCategory = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctxCategory, {
            type: 'pie',
            data: categoryData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Data untuk diagram tambahan
        const extraData = {
            labels: ['A', 'B', 'C', 'D', 'E'],
            datasets: [{
                label: 'Data Tambahan',
                data: [10, 15, 7, 20, 12],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#FF9800'],
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
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    });
</script>