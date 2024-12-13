<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>sidebar</title>
    <link rel="stylesheet" href="<?= base_url('css/style.sidebar.css') ?>" />
    <link
        href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
        rel="stylesheet" />
</head>

<body>

    <?= view("Components/navbar") ?>

    <main class="container-side">
        <div class="dashboard">
            <a href="/home/dashboard" class="container-dashboard">
                <img src="<?= base_url("img/dashboard.png") ?>" alt="" />
                <h1 class="dashboard-text">Dashboard</h1>
            </a>
        </div>
        <div class="container-navigation">
            <h2 class="navigation-subtitle">Peminjaman Buku</h2>
            <div class="navigation">
                <div class="subnavigation">
                    <div class="nav">
                        <a class="nav-data" href="javascript:void(0);">
                            <img src="<?= base_url("img/Vector.png") ?>" alt="" />
                            <h3 class="text-nav">Master Data</h3>
                            <i class="bx bx-chevron-down"></i>
                        </a>
                        <!-- Sub-menu -->
                        <div class="sub-menu hidden">
                            <a href="kategori.html" class="sub-menu-item">
                                <i class="bx bx-category-alt"></i>
                                <p>Kategory</p>
                            </a>
                            <a href="nama-buku.html" class="sub-menu-item">
                                <i class="bx bx-book-alt"></i>
                                <p>Buku</p>
                            </a>
                            <a href="anggota.html" class="sub-menu-item">
                                <i class="bx bx-group"></i>
                                <p>Anggota</p>
                            </a>
                        </div>
                    </div>

                    <div class="nav">
                        <a class="nav-pinjam" href="">
                            <img src="<?= base_url("img/Group 17.png") ?>" alt="" />
                            <h3 class="text-nav">Peminjaman Buku</h3>
                        </a>
                    </div>
                    <div class="nav">
                        <a class="nav-laporan" href="javascript:void(0);">
                            <img src="<?= base_url("img/Group 18.png") ?>" alt="" />
                            <h3 class="text-nav">Laporan</h3>
                            <i class="bx bx-chevron-down"></i>
                        </a>
                        <!-- Sub-menu -->
                        <div class="sub-menu hidden">
                            <a href="riwayat.html" class="sub-menu-item">
                                <i class="bx bx-history"></i>
                                <p>Riwayat</p>
                            </a>
                            <a href="laporan-peminjaman.html" class="sub-menu-item">
                                <i class='bx bxs-report'></i>
                                <p>laporan peminjaman</p>
                            </a>

                        </div>
                    </div>

                    <div class="nav">
                        <a class="nav-admin" href="">
                            <img src="<?= base_url("img/Group 19.png") ?>" alt="" />
                            <h3 class="text-nav">admin</h3>
                        </a>
                    </div>
                </div>
                <div class="exits">
                    <a href="<?= base_url('home/logout') ?>">
                        <i class='bx bx-exit'></i>
                        <h3 class="text-nav">Keluar</h3>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Untuk Master Data
            const masterData = document.querySelector(".nav-data");
            const masterSubMenu = masterData.nextElementSibling;

            masterData.addEventListener("click", () => {
                masterSubMenu.classList.toggle("visible");
            });

            // Untuk Laporan
            const laporan = document.querySelector(".nav-laporan");
            const laporanSubMenu = laporan.nextElementSibling;

            laporan.addEventListener("click", () => {
                laporanSubMenu.classList.toggle("visible");
            });
        });
    </script>
</body>

</html>