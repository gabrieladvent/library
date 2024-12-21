<head>
    <link rel="stylesheet" href="<?= base_url('css/style.sidebar.css') ?>">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>

    <main class="container-side">
        <div class="dashboard">
            <a href="/home/dashboard" class="container-dashboard" id="dashboard-link">
                <img src="<?= base_url("img/dashboard.png") ?>" alt="" />
                <h1 class="dashboard-text">Dashboard</h1>
            </a>
        </div>
        <div class="container-navigation">
            <h2 class="navigation-subtitle">Peminjaman Buku</h2>
            <div class="navigation">
                <div class="subnavigation">
                    <!-- Master Data -->
                    <div class="nav">
                        <a class="nav-data" href="javascript:void(0);" id="master-data-toggle">
                            <img src="<?= base_url("img/Vector.png") ?>" alt="" />
                            <h3 class="text-nav">Master Data</h3>
                            <i class="bx bx-chevron-down"></i>
                        </a>
                        <div class="sub-menu hidden" id="master-data-menu">
                            <a href="kategori.html" class="sub-menu-item" id="kategori-link">
                                <i class="bx bx-category-alt"></i>
                                <p>Kategori</p>
                            </a>
                            <a href="<?= base_url('home/buku') ?>" class="sub-menu-item" id="buku-link">
                                <i class="bx bx-book-alt"></i>
                                <p>Buku</p>
                            </a>
                            <a href="anggota.html" class="sub-menu-item" id="anggota-link">
                                <i class="bx bx-group"></i>
                                <p>Anggota</p>
                            </a>
                        </div>
                    </div>

                    <!-- Peminjaman Buku -->
                    <div class="nav">
                        <a class="nav-pinjam" href="#" id="pinjam-buku-link">
                            <img src="<?= base_url("img/Group 17.png") ?>" alt="" />
                            <h3 class="text-nav">Peminjaman Buku</h3>
                        </a>
                    </div>

                    <!-- Laporan -->
                    <div class="nav">
                        <a class="nav-laporan" href="javascript:void(0);" id="laporan-toggle">
                            <img src="<?= base_url("img/Group 18.png") ?>" alt="" />
                            <h3 class="text-nav">Laporan</h3>
                            <i class="bx bx-chevron-down"></i>
                        </a>
                        <div class="sub-menu hidden" id="laporan-menu">
                            <a href="riwayat.html" class="sub-menu-item" id="riwayat-link">
                                <i class="bx bx-history"></i>
                                <p>Riwayat</p>
                            </a>
                            <a href="laporan-peminjaman.html" class="sub-menu-item" id="laporan-peminjaman-link">
                                <i class='bx bxs-report'></i>
                                <p>Laporan Peminjaman</p>
                            </a>
                        </div>
                    </div>

                    <!-- Admin -->
                    <div class="nav">
                        <a class="nav-admin" href="#" id="admin-link">
                            <img src="<?= base_url("img/Group 19.png") ?>" alt="" />
                            <h3 class="text-nav">Admin</h3>
                        </a>
                    </div>
                </div>

                <!-- Exit -->
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
            // Menentukan elemen-elemen yang perlu dikelola
            const sidebarLinks = document.querySelectorAll(".nav a");
            const subMenus = document.querySelectorAll(".sub-menu");
            const masterDataToggle = document.querySelector("#master-data-toggle");
            const laporanToggle = document.querySelector("#laporan-toggle");

            // Cek apakah status menu dan link aktif tersimpan di localStorage
            sidebarLinks.forEach(link => {
                const linkId = link.getAttribute("id");

                if (localStorage.getItem(linkId) === "active") {
                    link.classList.add("active");
                }
            });

            // Cek apakah submenu yang sesuai harus terbuka
            if (localStorage.getItem("masterDataOpen") === "true") {
                document.querySelector("#master-data-menu").classList.add("visible");
            }
            if (localStorage.getItem("laporanOpen") === "true") {
                document.querySelector("#laporan-menu").classList.add("visible");
            }

            // Event listener untuk setiap link di sidebar
            sidebarLinks.forEach(link => {
                link.addEventListener("click", (e) => {
                    // Menghapus kelas 'active' dari semua link
                    sidebarLinks.forEach(link => link.classList.remove("active"));

                    // Menambahkan kelas 'active' pada link yang diklik
                    link.classList.add("active");

                    // Menyimpan status link yang terakhir diklik di localStorage
                    localStorage.setItem(link.getAttribute("id"), "active");
                });
            });

            // Event listener untuk membuka/tutup sub-menu Master Data
            masterDataToggle.addEventListener("click", () => {
                const menu = document.querySelector("#master-data-menu");
                menu.classList.toggle("visible");
                // Simpan status open atau close submenu Master Data
                localStorage.setItem("masterDataOpen", menu.classList.contains("visible"));
            });

            // Event listener untuk membuka/tutup sub-menu Laporan
            laporanToggle.addEventListener("click", () => {
                const menu = document.querySelector("#laporan-menu");
                menu.classList.toggle("visible");
                // Simpan status open atau close submenu Laporan
                localStorage.setItem("laporanOpen", menu.classList.contains("visible"));
            });
        });
    </script>

</body>