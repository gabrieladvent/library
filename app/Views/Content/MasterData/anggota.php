<?= $this->extend('Layouts/default') ?>

<?php $this->section('content');
$encrypter = \Config\Services::encrypter(); ?>



<body>
    <div class="container-book">
        <!-- table -->
        <div class="container-buku">
            <div class="head">
                <div class="title">
                    <h1>Data Anggota </h1>
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
                                <th>NIS</th>
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
                                        <td class="email"><?= $key['fullname'] ?> </td>
                                        <td class="email"><?= $key['identification'] ?> </td>
                                        <td class="email"><?= $key['address'] ?> </td>
                                        <td class="email"><?= $key['phone'] ?> </td>
                                        <td class="email"><?= $key['created_at'] ?> </td>

                                        <td>
                                            <div class="action-buttons">

                                                <a href="#popup__delete" class="btn btn-view">
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
                                                <select class="input-count" id="class_id" name="class_name" required>
                                                    <option value="">Pilih Kelas</option>
                                                    <optgroup label="Kelas 10">
                                                        <option value="X-A">X-A</option>
                                                        <option value="X-B">X-B</option>
                                                        <option value="X-C">X-C</option>
                                                    </optgroup>
                                                    <optgroup label="Kelas 11 IPA">
                                                        <option value="XI IPA-1">XI IPA-1</option>
                                                        <option value="XI IPA-2">XI IPA-2</option>
                                                        <option value="XI IPA-3">XI IPA-3</option>
                                                    </optgroup>
                                                    <optgroup label="Kelas 11 IPS">
                                                        <option value="XI IPS-1">XI IPS-1</option>
                                                        <option value="XI IPS-2">XI IPS-2</option>
                                                        <option value="XI IPS-3">XI IPS-3</option>
                                                    </optgroup>
                                                    <optgroup label="Kelas 12 IPA">
                                                        <option value="XII IPA-1">XII IPA-1</option>
                                                        <option value="XII IPA-2">XII IPA-2</option>
                                                        <option value="XII IPA-3">XII IPA-3</option>
                                                    </optgroup>
                                                    <optgroup label="Kelas 12 IPS">
                                                        <option value="XII IPS-1">XII IPS-1</option>
                                                        <option value="XII IPS-2">XII IPS-2</option>
                                                        <option value="XII IPS-3">XII IPS-3</option>
                                                    </optgroup>
                                                </select>


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
                    <div class="container__popup" id="popup__delete">
                        <div class="popup">
                            <div class="title">
                                <h1>Lihat Detail</h1>
                                <a href="#" class="popup-close">&times;</a>
                            </div>
                            <form id="formDetailUser" method="post" autocomplete="off">
                                <?= csrf_field() ?>
                                <div class="container__input">
                                    <div class="satu">
                                        <div class="input-content">
                                            <label class="label">Nama Anggota</label>
                                            <input class="input" type="text" id="fullname" name="fullname" readonly />
                                        </div>
                                        <div class="input-content">
                                            <label class="label">Nomor Induk Siswa</label>
                                            <input class="input" type="text" id="identifiction" name="identifiction" readonly />
                                        </div>
                                        <div class="input-content">
                                            <label class="label">Jenis kelamin</label>
                                            <input class="input" type="text" id="gender" name="gender" readonly />
                                        </div>
                                        <div class="input-content">
                                            <label class="label">Agama</label>
                                            <input class="input" type="text" id="religion" name="religion" readonly />
                                        </div>
                                    </div>
                                    <div class="dua">
                                        <div class="input-content">
                                            <label class="label">Tempat Lahir</label>
                                            <input class="input" type="text" id="place_birth" name="place_birth" readonly />
                                        </div>
                                        <div class="input-content">
                                            <label class="label">Tanggal Lahir</label>
                                            <input class="input" type="date" id="date_birth" name="date_birth" readonly />
                                        </div>
                                        <div class="input-content">
                                            <label class="label">Nomor Telepon</label>
                                            <input class="input" type="text" id="phone" name="phone" readonly />
                                        </div>
                                        <div class="input-content">
                                            <label class="label">Alamat Domisili</label>
                                            <textarea class="input alamat" id="address" name="address" rows="4" cols="50" readonly></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="button">
                                    <button class="batal" type="button">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="container__popup" id="popup__edit">
                        <div class="popup">
                            <div class="title">
                                <h1>Tambah Anggota</h1>
                                <a href="#" class="popup-close">&times;</a>
                            </div>
                            <form action="user/add" method="post" autocomplete="off">
                                <?= csrf_field() ?>
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
                                            <input class="input" type="tel" id="phone" name="phone" autocomplete="off">
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