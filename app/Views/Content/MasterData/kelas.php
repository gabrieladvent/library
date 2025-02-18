<?= $this->extend('Layouts/default') ?>


<?= $this->section('content') ?>



<body>
    <div class="container-book  ">
        <!-- table -->
        <div class="container-buku">
            <div class="head">
                <div class="title">
                    <h1>Data Kelas</h1>
                </div>
                <a href="#popup_addclass" class="tambah-buku">
                    <p>Tambah Data</p>
                    <i class='bx bxs-plus-square'></i>
                </a>
            </div>
            <div class="container-table">
                <div class="table kelas">
                    <table border="1">
                        <thead>
                            <tr>
                                <th class="th-no">No</th>
                                <th class="th-nama">Nama Kelas</th>
                                <th class="th-aksi">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($all_classes)): ?>
                                <?php foreach ($all_classes as $index => $class): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td class="email"><?= $class["class_name"] ?></td>


                                        <td>
                                            <div class="action-buttons">
                                                <button onclick="viewDetailClass(this)" class="btn btn-view" data-id="<?= $class['id'] ?>">
                                                    <i class="bx bx-edit"></i> Kelolah
                                                </button>
                                                <button class="btn btn-edit">
                                                    <i class="bx bx-trash"></i> Hapus
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center">Data Tidak Tersedia</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                    <div class="container__popup" id="popup_addclass">
                        <div class="popup_AddClas">
                            <div class="title">
                                <h1>Tambah Kelas</h1>
                                <a href="#" class="popup-close">&times;</a>
                            </div>
                            <form action="<?= base_url('class/add') ?>" method="post" autocomplete="off" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="container__input">
                                    <div class="status_input">
                                        <div class="input-content status">
                                            <label class="label" for="">Masukan Nama Kelas</label>
                                            <input class="input-user" type="text" name="class_name" placeholder="contoh x-A">
                                        </div>

                                    </div>
                                </div>
                                <div class="button">
                                    <button class="batal" type="button">Batal</button>
                                    <button class="simpan" type="submit">Simpan</button>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="container__popup" id="popup_viewClass">
                        <div class="popup_viewclass">
                            <div class="title">
                                <div class="form-group">
                                    <h1>Lihat Data</h1>
                                    <input type="checkbox" id="enableEdit" onchange="toggleEditClass(this)">
                                    <label for="enableEdit">Aktifkan Mode Edit</label>
                                </div>
                                <a href="#" id="popup__close" class="popup-close">&times;</a>
                            </div>
                            <form id="formDetailUser" method="POST" autocomplete="off" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="container__input">
                                    <div class="status_input">
                                        <div class="input-content status">
                                            <label class="label" for="">Masukan Nama Kelas</label>
                                            <input class="input-user" type="text" id="class_name" name="class_name" disabled>
                                        </div>

                                    </div>
                                </div>
                                <div class="button">
                                    <button type="button" class="batal" onclick="closePopupClass()">Batal</button>
                                    <button class="simpan" type="submit">Simpan</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    </div>


    <?= $this->endSection() ?>
</body>