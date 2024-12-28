<?= $this->extend('Layouts/default') ?>


<?= $this->section('content') ?>

<head>

    <link rel="stylesheet" href="<?= base_url("css/style.table.css") ?>" />
    <link
        href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
        rel="stylesheet" />
</head>

<body>
    <div class="container-book  ">
        <!-- table -->
        <div class="container-buku">
            <div class="head">
                <div class="title">
                    <h1>Data Anggota</h1>
                </div>
                <div class="tambah-buku">
                    <p>Tambah Data</p>
                    <i class='bx bxs-plus-square'></i>

                </div>
            </div>
            <div class="container-table">
                <div class="table">
                    <table border="1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th class="th-nama">Nama Anggota</th>
                                <th>NIS</th>
                                <th class="th-alamat">Alamat</th>
                                <th>Nomor Hp</th>
                                <th class="th-tgl">Tanggal Bergabung</th>
                                <th class="th-aksi">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Blasius Chelvyn Kera Kleden</td>
                                <td>2343490593203</td>
                                <td class="td-alamat">Jl. Paingan No.4 No.47, Krodan, Maguwoharjo, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281</td>
                                <td>082534553449</td>
                                <td>12 januari 2023</td>


                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-view">
                                            <i class="bx bx-show"></i> Lihat
                                        </button>
                                        <button class="btn btn-edit">
                                            <i class="bx bx-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-delete">
                                            <i class="bx bx-trash"></i> Hapus
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

    </div>


</body>
<?= $this->endSection() ?>

</html>