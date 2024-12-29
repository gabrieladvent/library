<?= $this->extend('Layouts/default') ?>

<?= $this->section('content') ?>

<head>
    <link rel="stylesheet" href="<?= base_url("css/style.table.css") ?>" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container-book">
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
                            <?php if (!empty($detail_user)): ?>
                                <tr>
                                    <td>1</td>
                                    <td><?= $detail_user['nama']; ?></td>
                                    <td><?= $detail_user['nis']; ?></td>
                                    <td class="td-alamat"><?= $detail_user['alamat']; ?></td>
                                    <td><?= $detail_user['nomor_hp']; ?></td>
                                    <td><?= date('d F Y', strtotime($detail_user['tanggal_bergabung'])); ?></td>
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
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center;">Data tidak tersedia.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

<?= $this->endSection() ?>