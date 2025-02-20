<head>
    <link rel="stylesheet" href="<?= base_url('css/style.sidebar.css') ?>">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script type="text/javascript" src="<?= base_url('js/sidenavigation.js') ?>" defer></script>
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
                <div class="subnavigation" id="sidebar">
                    <!-- Master Data -->
                    <div class="nav" id="nav-link">
                        <button onclick=toggleSubMenu(this) class="dropdown-btn" id="master-data-toggle">
                            <img src="<?= base_url("img/Vector.png") ?>" alt="" />
                            <h3 class="text-nav">Master Data</h3>
                            <i class="bx bx-chevron-down svg"></i>
                        </button>
                        <div class="sub-menu" id="">
                            <div>
                                <a href="<?= base_url('class/all') ?>" class="sub-menu-item" id="kategori-link">
                                    <i class='bx bxs-home-smile'></i>
                                    <p>Class</p>
                                </a>
                                <a href="<?= base_url('categori/view') ?>" class="sub-menu-item" id="kategori-link">
                                    <i class="bx bx-category-alt"></i>
                                    <p>Kategori</p>
                                </a>
                                <a href="<?= base_url('book/dashboard') ?>" class="sub-menu-item buku" id="buku-link">
                                    <i class="bx bx-book-alt"></i>
                                    <p>Buku</p>
                                </a>
                                <a href="<?= base_url('user/list/Anggota') ?>" class="sub-menu-item" id="anggota-link">
                                    <i class="bx bx-group"></i>
                                    <p>Anggota</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Peminjaman Buku -->
                    <div class="nav">
                        <a class="nav-pinjam" href="/home/list" id="pinjam-buku-link">
                            <img src="<?= base_url("img/Group 17.png") ?>" alt="" />
                            <h3 class="text-nav">Peminjaman Buku</h3>
                        </a>
                    </div>

                    <!-- Laporan -->
                    <div class="nav" id="nav-link">
                        <button onclick=toggleSubMenu(this) class="dropdown-btn" id="laporan-toggle">
                            <img src="<?= base_url("img/Group 18.png") ?>" alt="" />
                            <h3 class="text-nav">Laporan</h3>
                            <i class="bx bx-chevron-down svg"></i>
                        </button>
                        <div class="sub-menu" id="laporan-menu">
                            <div>
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
                    </div>

                    <!-- Admin -->
                    <div class="nav">
                        <a class="nav-admin" href="<?= base_url('/user/list/Admin') ?>" id="pinjam-admin-link">
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



</body>