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
                    <h1>Data Kategori</h1>
                </div>
                <a href="#popup_addcategory" class="tambah">
                    <p>Tambah Data</p>
                    <i class='bx bxs-plus-square'></i>
                </a>
            </div>

            <div class="container-table">
                <div class="table">
                    <table border="1">
                        <thead>
                            <tr>
                                <th class="th-no">No</th>
                                <th class="th-kategory">Category</th>
                                <th class="th-deskripsi">Deskripsi</th>
                                <th class="th-aksi">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td>1</td>
                                <td class="email">nama</td>
                                <td class="email">hahahah</td>

                                <td>
                                    <div class="action-buttons">
                                        <button onclick="viewDetailCategory(this)" class="btn btn-view" data-id="1">
                                            <i class="bx bx-edit"></i> Kelolah
                                        </button>

                                        <button class="btn btn-edit" onclick="DeleteClass(this)" data-id="1" data-name="fisika">
                                            <i class="bx bx-trash"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                        </tbody>


                    </table>
                    <!-- add kategory -->
                    <div class="container__popup" id="popup_addcategory">
                        <div class="popup_AddCategory">
                            <div class="title">
                                <h1>Tambah Kategori</h1>
                                <a href="" class="popup-close">&times;</a>
                            </div>
                            <form action="<?= base_url('class/add') ?>" method="post" autocomplete="off" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="container__input">
                                    <div class="status_input">
                                        <div class="input-content status">
                                            <label class="label" for="">Masukan Nama Kategory</label>
                                            <input class="input-user" type="text" name="Category" placeholder="contoh Fisika">
                                        </div>

                                    </div>
                                </div>
                                <div class="button">
                                    <button class="batal batal_add" type="button">Batal</button>
                                    <button class="simpan" type="submit">Simpan</button>
                                </div>
                            </form>

                        </div>
                    </div>

                    <!-- view category -->
                    <div class="container__popup" id="popup_viewCategory">
                        <div class="popup_viewcategory">
                            <div class="title">
                                <div class="form-group">
                                    <h1>Lihat Data</h1>
                                    <input type="checkbox" id="enableEdit" onchange="toggleEditCategory(this)">
                                    <label for="enableEdit">Aktifkan Mode Edit</label>
                                </div>
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
                                    <button type="button" class="batal" onclick="closePopupCategory()">Batal</button>
                                    <button class="simpan" type="submit">Simpan</button>
                                </div>
                            </form>

                        </div>
                    </div>

                    <!-- delete category -->
                    <div id="popup__delete" class="container__popup">
                        <div class="popup_delete">
                            <div class="title_delete">
                                <div class="form-group">
                                    <h1>Konfirmasi Hapus</h1>

                                </div>
                            </div>
                            <div class="popup__content">
                                <div class="title_delete">
                                    <h3>Apakah anda yakin ingin menghapus admin?</h3>
                                    <p></p>
                                </div>
                                <div class="button-delete">
                                    <button type="button" class="batal" onclick="closeDeletePopup()">Batal</button>
                                    <button type="button" class="simpan" id="confirmDelete">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>


</body>
<script type="text/javascript" src="<?= base_url('js/category.js') ?>"></script>

<?= $this->endSection() ?>

</html>