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
                                                <button onclick="viewDetailAdmin(this)" class="btn btn-view" data-id="<?= urlencode(base64_encode($encrypter->encrypt($key['id']))) ?>">
                                                    <i class="bx bx-edit"></i> Kelolah
                                                </button>


                                                <button class="btn btn-edit" onclick="DeleteAdmin(this)" data-id="<?= urlencode(base64_encode($encrypter->encrypt($key['id']))) ?>" data-name="<?= $key['username'] ?>" data-type="Admin">
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
                    <div class="container__popup" id="popup">
                        <div class="popup">
                            <div class="title">
                                <h1>Tambah Byu</h1>
                                <a href="#" class="popup-close">&times;</a>
                            </div>
                            <form action="<?= base_url('user/add') ?>" method="post" autocomplete="off" enctype="multipart/form-data" onsubmit="return validationPasswordAdmin()">
                                <?= csrf_field() ?>
                                <div class="container__input">
                                    <div class="satu">
                                        <div class="status_input">
                                            <div class="input-content status">
                                                <label class="label" for="">Status</label>
                                                <input class="input status" type="text" name="role" value="Admin" readonly />

                                            </div>
                                            <div class="input-content status">
                                                <label class="label" for="">Username/Email</label>
                                                <input class="input-user" type="text" name="username_email">
                                            </div>
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Nama Lengkap</label>
                                            <input class="input" type="text" name="fullname" />
                                        </div>

                                        <!-- <div class="input-content">
                                            <label class="label" for="">email</label>
                                            <input class="input" type="text" name="email" />
                                        </div> -->




                                        <div class="count_book">
                                            <div class="input-jumlah">
                                                <label class="label" for="">Jenis Kelamin</label>
                                                <select class="input-count" name="gender" required>
                                                    <option value="">Pilih</option>
                                                    <option value="Laki-Laki">1. Laki-Laki</option>
                                                    <option value="Perempuan">2. Perempuan</option>

                                                </select>
                                            </div>
                                            <div class="input-jumlah">
                                                <label class="label" for="">Agama</label>
                                                <select class="input-count" name="religion" required>
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
                                            <input class="input" type="Password" name="password" id="password" />
                                        </div>

                                        <div class="input-content">
                                            <label class="label" for="">Konfimasi Password</label>
                                            <input class="input" type="Password" name="password_confirm" id="konfiPassword" />
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
                                    <input type="checkbox" id="enableEdit" onchange="toggleEditAdmin(this)">
                                    <label for="enableEdit">Aktifkan Mode Edit</label>
                                </div>

                                <a href="#" id="popup__close" class="popup-close">&times;</a>
                            </div>
                            <form id="formDetailUser" method="POST" autocomplete="off" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="container__input">
                                    <div class="satu">
                                        <div class="status_input">
                                            <div class="input-content status">
                                                <label class="label" for="">Status</label>
                                                <input class="input status" type="text" name="role" value="Admin" readonly />


                                            </div>
                                            <div class="input-content status">
                                                <label class="label" for="">User Name</label>
                                                <input class="input-user" id="username" type="text" name="username_email" disabled>
                                            </div>
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Nama Lengkap</label>
                                            <input class="input" id="fullname" type="text" name="fullname" disabled />
                                        </div>




                                        <div class="count_book">
                                            <div class="input-jumlah">
                                                <label class="label" for="">Jenis Kelamin</label>
                                                <select class="input-count" id="gender" name="gender" required disabled>
                                                    <option id="gender" value="">Pilih</option>
                                                    <option value="Laki-Laki">1. Laki-Laki</option>
                                                    <option value="Perempuan">2. Perempuan</option>

                                                </select>
                                            </div>
                                            <div class="input-jumlah">
                                                <label class="label" for="">Agama</label>
                                                <select class="input-count" id="religion" name="religion" required disabled>
                                                    <option id="religion" value="">Pilih</option>
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
                                                <input class="input-count" id="place_birth" type="text" name="place_birth" disabled>

                                            </div>
                                            <div class="input-jumlah">
                                                <label class="label" for="">Tanggal Lahir</label>
                                                <input class="input-count" id="date_birth" type="date" name="date_birth" disabled>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="dua admin">

                                        <div class="count_book">
                                            <div class="input-jumlah">
                                                <label class="label" for="">Nomor Telephone</label>
                                                <input class="input-count" type="number" id="phone" name="phone" disabled>

                                            </div>
                                            <div class="input-jumlah">
                                                <label class="label" for="">NIP</label>
                                                <input class="input-count" type="number" id="identification" name="identification" disabled>
                                            </div>
                                        </div>
                                        <div id="input_password" style="display: none;">
                                            <div class="input-content">
                                                <label class="label" for="password">Password</label>
                                                <input class="input" type="password" id="password" name="password" />
                                            </div>
                                            <div class="input-content">
                                                <label class="label" for="konfiPassword">Konfirmasi Password</label>
                                                <input class="input" type="password" id="konfiPassword" name="password_confirm" />
                                            </div>
                                        </div>


                                        <div class=" input-content">
                                            <label class="label" for="">alamat </label>
                                            <textarea class="input alamat" id="address" name="address" rows="4" cols="50" placeholder="Masukkan alamat lengkap Anda" required disabled></textarea>

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
</body>

<?= $this->endSection() ?>