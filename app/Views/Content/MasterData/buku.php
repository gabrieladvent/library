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
                    <h1>Data Buku</h1>
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
                                <th>Judul Buku</th>
                                <th>Pengarang</th>
                                <th>Penerbit</th>
                                <th class="th-kategory">Tahun Terbit</th>
                                <th>Kategori</th>
                                <th class="th-jumlah">Jumlah Buku</th>
                                <th class="th-sampul">Sampul</th>
                                <th class="th-aksi">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($book)): ?>
                                <?php foreach ($book as $index => $key): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td class="buku"><?= $key['book_name']  ?> </td>
                                        <td class="buku"><?= $key['author']  ?> </td>
                                        <td>
                                            <div class="action-buttons">

                                                <a href="<?= base_url('user/detail') ?> ?users=<?= urlencode(base64_encode($encrypter->encrypt($key['id']))) ?>" class="btn btn-view">
                                                    <i class="bx bx-show"></i> Lihat
                                                </a>


                                                <button class="btn btn-edit">
                                                    <i class="bx bx-edit"></i> Edit
                                                </button>
                                                <button class="btn btn-delete">
                                                    <i class="bx bx-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
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

    </div>


</body>
<?= $this->endSection() ?>

</html>