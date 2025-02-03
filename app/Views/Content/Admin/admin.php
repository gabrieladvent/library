<?= $this->extend('Layouts/default') ?>

<?php $this->section('content');
$encrypter = \Config\Services::encrypter(); ?>

<head>
    <link rel="stylesheet" href="<?= base_url("css/style.table.css") ?>" />
    <link rel="stylesheet" href="<?= base_url("css/style.popup.css") ?>" />

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container-book">
        <!-- table -->
        <div class="container-buku">
            <div class="head">
                <div class="title">
                    <h1>Data Admin </h1>
                </div>
                <a href="#popup" class="tambah-buku">
                    <p>Tambah Data</p>
                    <i class='bx bxs-plus-square'></i>
                </a>
            </div>
            <div class="container-table">
                <div class="table">
                    <table border="1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Anggota</th>
                                <th>Full Name</th>
                                <th>Alamat</th>
                                <th>Nomor Hp</th>
                                <th>Tanggal Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($list_user)): ?>
                                <?php foreach ($list_user as $index => $key): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td class="email"><?= $key['username'] ?> </td>
                                        <td class="email"><?= $key['fullname'] ?> </td>
                                        <td class="email"><?= $key['address'] ?> </td>
                                        <td class="email"><?= $key['phone'] ?> </td>
                                        <td class="email"><?= $key['created_at'] ?> </td>

                                        <td>
                                            <div class="action-buttons">

                                                <a href="#popup__lihat" class="btn btn-view">
                                                    <i class="bx bx-edit"></i> Kelolah
                                                </a>


                                                <a href="#popup__edit" class="btn btn-edit">
                                                    <i class="bx bx-trash"></i> Hapus
                                                </a>

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
                    <div class="container__popup" id="popup">
                        <div class="popup">
                            <div class="title">
                                <h1>Tambah Byu</h1>
                                <a href="#" class="popup-close">&times;</a>
                            </div>
                            <form action="<?= base_url('user/add') ?>" method="post" autocomplete="off" enctype="multipart/form-data" onsubmit="return validationPassword()">
                                <?= csrf_field() ?>
                                <div class="container__input">
                                    <div class="satu">
                                        <div class="status_input">
                                            <div class="input-content status">
                                                <label class="label" for="">Status</label>
                                                <input class="input status" type="text" name="role" value="admin" disabled />

                                            </div>
                                            <div class="input-content status">
                                                <label class="label" for="">User Name</label>
                                                <input class="input-user" type="text" name="username_email">
                                            </div>
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Nama Lengkap</label>
                                            <input class="input" type="text" name="fullname" />
                                        </div>

                                        <div class="input-content">
                                            <label class="label" for="">email</label>
                                            <input class="input" type="text" name="email" />
                                        </div>




                                        <div class="count_book">
                                            <div class="input-jumlah">
                                                <label class="label" for="">Jenis Kelamin</label>
                                                <select class="input-count" id="category_id" name="gender" required>
                                                    <option value="">Pilih</option>
                                                    <option value="Laki-Laki">1. Laki-Laki</option>
                                                    <option value="Perempuan">2. Perempuan</option>

                                                </select>
                                            </div>
                                            <div class="input-jumlah">
                                                <label class="label" for="">Jenis Kelamin</label>
                                                <select class="input-count" id="category_id" name="religion" required>
                                                    <option value="">Pilih</option>
                                                    <option value="Islam">1. Islam</option>
                                                    <option value="Kristen">2. Kristen</option>
                                                    <option value="Katolik">3. Katolik</option>
                                                    <option value="Hindu">4. Hindu</option>
                                                    <option value="Buddha">5. Buddha</option>
                                                    <option value="Konghucu">6. Konghucu</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="count_book">
                                            <div class="input-jumlah">
                                                <label class="label" for="">Tempat Lahir</label>
                                                <input class="input-count" type="text" name="place_birth">

                                            </div>
                                            <div class="input-jumlah">
                                                <label class="label" for="">Tanggal Lahir</label>
                                                <input class="input-count" type="date" name="date_birth">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="dua admin">

                                        <div class="count_book">
                                            <div class="input-jumlah">
                                                <label class="label" for="">Nomor Telephone</label>
                                                <input class="input-count" type="number" name="phone">

                                            </div>
                                            <div class="input-jumlah">
                                                <label class="label" for="">NIP</label>
                                                <input class="input-count" type="number" name="identification">
                                            </div>
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Password</label>
                                            <input class="input" type="Password" name="password" />
                                        </div>

                                        <div class="input-content">
                                            <label class="label" for="">Konfimasi Password</label>
                                            <input class="input" type="Password" name="password_confirm" />
                                        </div>

                                        <div class=" input-content">
                                            <label class="label" for="">alamat </label>
                                            <textarea class="input alamat" id="alamat" name="address" rows="4" cols="50" placeholder="Masukkan alamat lengkap Anda" required></textarea>

                                            <div>
                                            </div>
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
                    <div class="container__popup" id="popup__lihat">
                        <div class="popup">
                            <div class="title">
                                <div class="form-group">
                                    <h1>Lihat Data</h1>
                                    <input type="checkbox" id="enableEdit" onchange="toggleEdit(this)">
                                    <label for="enableEdit">Aktifkan Mode Edit</label>
                                </div>

                                <a href="#" id="popup__close" class="popup-close">&times;</a>
                            </div>
                            <form id="formDetailUser" method=" post" autocomplete="off" enctype="multipart/form-data">
                                <?= csrf_field() ?>

                                <div class="container__input">
                                    <div class="satu">
                                        <div class="input-content">
                                            <label class="label">Jenis Buku</label>
                                            <select class="input" id="category_name" name="category_name" disabled>
                                                <option id="category_name" value="">Pilih Jenis Buku</option>
                                                <option value="1">1. Fiksi</option>
                                                <option value="2">2. Novel</option>
                                                <option value="3">3. Sains</option>
                                            </select>

                                        </div>
                                        <div class="input-content">
                                            <label class="label">Nama Buku</label>
                                            <input class="input" type="text" id="fullname" name="book_name" disabled />
                                        </div>
                                        <div class="input-content">
                                            <label class="label">ISBN</label>
                                            <input class="input" type="text" id="isbn" name="isbn" disabled />
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Author</label>
                                            <input class="input" type="text" id="author" name="author" disabled />
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Cover Image</label>
                                            <div class="img">
                                                <img id="cover_img_view" alt="cover image" style="max-width: 200px;">
                                                <input type="file" id="cover_image" name="cover_img" accept="image/*" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dua">
                                        <div class=" input-content">
                                            <label class="label">Penebit</label>
                                            <input class="input" type="text" id="publisher" name="publisher" disabled />
                                        </div>

                                        <div class="input-content">
                                            <label class="label" for="">Tahun Terbit</label>
                                            <input class="input" type="number" id="year_published" name="year_published" min="1900" disabled />
                                        </div>
                                        <div class="count_book">
                                            <div class="input-jumlah">
                                                <label class="label" for="">Total copy</label>
                                                <input class="input-count" type="number" id="total_copies" name="total_copies" disabled>
                                            </div>
                                            <div class="input-jumlah">
                                                <label class="label" for="">jumlah Buku</label>
                                                <input class="input-count" type="number" id="total_books" name="total_books" min="1" max="1000" step="1" disabled>
                                            </div>
                                        </div>

                                        <div class="input-content">
                                            <label class="label" for="">Deskripsi Buku </label>
                                            <textarea class="input alamat" id="description" name="description" rows="4" cols="50" placeholder="Masukkan alamat lengkap Anda" disabled></textarea>


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
                    <div id="popup__delete" class="container__popup">
                        <div class="popup_delete">
                            <div class="title_delete">
                                <div class="form-group">
                                    <h1>Konfirmasi Hapus</h1>

                                </div>
                            </div>
                            <div class="popup__content">
                                <div class="title_delete">
                                    <h3>Apakah anda yakin ingin menghapus buku ini?</h3>
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
</body>

<?= $this->endSection() ?>