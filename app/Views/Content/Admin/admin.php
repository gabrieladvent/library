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

                                                <a href="#popup" class="btn btn-view">
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
                    <div class="container__popup" id="popup">
                        <div class="popup">
                            <div class="title">
                                <h1>Tambah Anggota</h1>
                                <a href="#" class="popup-close">&times;</a>
                            </div>
                            <form action="">
                                <div class="container__input">
                                    <div class="satu">
                                        <div class="input-content">
                                            <label class="label" for="">Nama Anggota</label>
                                            <input class="input" type="text" name="fullname" />
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Nomor Induk Siswa</label>
                                            <input class="input" type="text" name="identifiction" />
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Jenis kelamin</label>
                                            <input class="input" type="text" name="gender" />
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">agama</label>
                                            <input class="input" type="text" name="religion" />
                                        </div>
                                    </div>
                                    <div class="dua">
                                        <div class="input-content">
                                            <label class="label" for="">Tempat lahir</label>
                                            <input class="input" type="text" name="place_birth" />
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Tanggal Lahir</label>
                                            <input class="input" type="date" name="date_birth" />
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Nomor Telpon</label>
                                            <input class="input" type="tel" id="phone" name="phone" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}">
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Alamat Domisili </label>
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
                    <div class="container__popup" id="popuplihat">
                        <div class="popup">
                            <div class="title">
                                <h1>Tambah Anggota</h1>
                                <a href="#" class="popup-close">&times;</a>
                            </div>
                            <form action="">
                                <div class="container__input">
                                    <div class="satu">
                                        <div class="input-content">
                                            <label class="label" for="">Nama Anggota</label>
                                            <input class="input" type="text" name="fullname" />
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Nomor Induk Siswa</label>
                                            <input class="input" type="text" name="identifiction" />
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Jenis kelamin</label>
                                            <input class="input" type="text" name="gender" />
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">agama</label>
                                            <input class="input" type="text" name="religion" />
                                        </div>
                                    </div>
                                    <div class="dua">
                                        <div class="input-content">
                                            <label class="label" for="">Tempat lahir</label>
                                            <input class="input" type="text" name="place_birth" />
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Tanggal Lahir</label>
                                            <input class="input" type="date" name="date_birth" />
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Nomor Telpon</label>
                                            <input class="input" type="tel" id="phone" name="phone" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}">
                                        </div>
                                        <div class="input-content">
                                            <label class="label" for="">Alamat Domisili </label>
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
                </div>
            </div>
        </div>
    </div>
</body>

<?= $this->endSection() ?>