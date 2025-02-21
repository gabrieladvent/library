<?= $this->extend('Layouts/default') ?>

<?php $this->section('content');
$encrypter = \Config\Services::encrypter();

?>

<div class="container-book">
    <div class="container-buku">
        <div class="head">
            <div class="title">
                <h1>Data Buku</h1>
            </div>
            <a href="#popuploans" class="tambah">
                <p>Tambah Data</p>
                <i class='bx bxs-plus-square'></i>
            </a>
        </div>

        <div class="container-table">
            <div class="table">
                <table border="1" id="peminjamanTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="th-kategory">Peminjam</th>
                            <th class="th-name">Judul Buku</th>
                            <th class="th-name">Penulis</th>
                            <th class="th-tanggal">Tanggal Peminjaman</th>
                            <th class="th-tanggal">Tanggal Pengembalian</th>
                            <th class="th-jumlah">Jumlah Buku</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <!-- <tbody>
                        <?php foreach ($loans as $loan => $index): ?>
                            <tr>
                                <td><?= $loan + 1 ?></td>
                                <td><?= $index['fullname'] ?></td>
                                <td><?= $index['book_name'] ?></td>
                                <td><?= implode(", ", json_decode($index['author'], true)) ?></td>
                                <td><?= date('d-m-Y', strtotime($index['loan_date'])) ?></td>
                                <td><?= date('d-m-Y', strtotime($index['return_date_expected'])) ?></td>
                                <td><?= $index['quantity'] ?></td>
                                <td>
                                    <div class="container_status">
                                        <?php
                                        $statusColors = [
                                            'Menunggu' => 'background-color: #e6c9a7; color: #3e3d3c',
                                            'Dipinjam' => 'background-color: rgb(236, 245, 164); color: #3e3d3c',
                                            'Diperpanjang' => 'background-color: rgb(163, 212, 244); color: #3e3d3c',
                                            'Dikembalikan' => 'background-color: rgb(136, 238, 155); color: #3e3d3c',
                                            'Terlambat' => 'background-color: rgb(241, 121, 121); color: #3e3d3c'
                                        ];

                                        $status = $index['status'];
                                        $style = isset($statusColors[$status]) ? $statusColors[$status] : 'background-color: #ccc; color: #3e3d3c';
                                        ?>
                                        <p class="status" style="<?= $style ?>"><?= htmlspecialchars($status) ?></p>
                                    </div>
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        <button onclick="viewDetailLoans(this)" class="btn btn-view" data-id="<?= urlencode(base64_encode($encrypter->encrypt($index['id']))) ?>">
                                            <i class="bx bx-edit"></i> Kelolah
                                        </button>
                                        <button class="btn btn-edit" onclick="Delete(this)" data-id="<?= urlencode(base64_encode($encrypter->encrypt($index['id']))) ?>">
                                            <i class="bx bx-trash"></i> Hapus
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody> -->

                    <!-- contoh -->
                    <tbody>


                        <tr>
                            <td>1</td>
                            <td>Andrea Hirata</td>
                            <td>Laskar Pelangi</td>
                            <td>Laskar Pelangi</td>
                            <td>2025-01-10</td>
                            <td>2025-01-20</td>
                            <td>2012</td>
                            <td>
                                <div class="container_status">
                                    <p class="status">dikembalikan</p>
                                </div>
                            </td>

                            <td>
                                <div class="action-buttons">
                                    <!-- Ubah button view menjadi: -->
                                    <button onclick="viewDetailLoans(this)" class="btn btn-view" data-id="">
                                        <i class="bx bx-edit"></i> Kelolah
                                    </button>
                                    <button class="btn btn-edit" onclick="Delete(this)" data-id="" data-name="">
                                        <i class="bx bx-trash"></i> Hapus
                                    </button>

                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>


                <!-- pop add peminjaman -->
                <div class="container__popup" id="popuploans">
                    <div class="popup_loans">
                        <div class="title">
                            <h1>Tambah Peminjaman</h1>
                            <a href="" class="popup-close">&times;</a>
                        </div>
                        <form action="<?= base_url('loans/add') ?>" method="post" autocomplete="off" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="container__input">
                                <div class="satu">
                                    <h1 class="subtitle">Formulir Peminjaman</h1>
                                    <div class="input-content">
                                        <label class="label" for="">Nama Anggota</label>
                                        <select class="input" id="status" name="fullname">
                                            <option id="loans_status" value="">anggota</option>
                                            <option value="2">Gab</option>
                                            <option value="3">Kelvin keleden</option>
                                        </select>
                                    </div>
                                    <div class="count_book">
                                        <div class="input-jumlah">
                                            <label class="label" for="">Kelas</label>
                                            <input class="input-count" type="text" value="X1-A" name="book_name" readonly />

                                        </div>


                                    </div>

                                    <div class="input-content">

                                        <label class="label" for="">Catatan </label>
                                        <textarea class="input alamat" id="alamat" name="description" rows="4" cols="50" placeholder="Masukkan alamat lengkap Anda" required></textarea>


                                    </div>

                                </div>
                                <div class="dua">
                                    <h1 class="subtitle">Formulir Peminjaman</h1>
                                    <div class="input-content">
                                        <label class="label" for="">Judul Buku</label>
                                        <select class="input" id="status" name="fullname">
                                            <option id="loans_status" value="">anggota</option>
                                            <option value="2">Gab</option>
                                            <option value="3">Kelvin keleden</option>
                                        </select>
                                    </div>
                                    <div class="count_book">
                                        <div class="input-jumlah">
                                            <label class="label" for="">tersedia</label>
                                            <input class="input-count" type="text" value="21" name="book_name" />

                                        </div>
                                        <div class="input-jumlah">
                                            <label class="label" for="">Jumlah Yang dipinjam</label>
                                            <input class="input-count" type="number" name="total_books" min="1" step="1" required>
                                        </div>
                                    </div>

                                    <div class="count_book">
                                        <div class="input-jumlah">
                                            <label class="label" for="">Tanggal Pengembalian</label>
                                            <input class="input-count" type="date" name="publisher" />
                                        </div>
                                        <div class="input-jumlah">
                                            <label class="label" for="">Kategori</label>
                                            <select class="input-count" id="status" name="fullname">
                                                <option id="loans_status" value="">Kategori</option>
                                                <option value="2">fiksi</option>
                                                <option value="3">nonfiksi</option>
                                            </select>
                                        </div>

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


                <!-- kololah peminjaman popup -->
                <div class="container__popup" id="popup__lihat">
                    <div class="popup">
                        <div class="title">
                            <div class="form-group">
                                <h1>Lihat Data</h1>
                                <input type="checkbox" id="enableEdit" onchange="toggleEdit(this)">
                                <label for="enableEdit">Aktifkan Mode Edit</label>
                            </div>
                        </div>
                        <form id="formDetailUser" method="post" autocomplete="off" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="container__input">
                                <div class="satu">
                                    <h1 class="subtitle">Formulir Peminjaman</h1>
                                    <div class="input-content">
                                        <label class="label" for="">Nama Anggota</label>
                                        <select class="input" id="status" name="fullname" disabled>
                                            <option id="loans_status" value="">anggota</option>
                                            <option value="2">Gab</option>
                                            <option value="3">Kelvin keleden</option>
                                        </select>
                                    </div>
                                    <div class="count_book">
                                        <div class="input-jumlah">
                                            <label class="label" for="">Kelas</label>
                                            <input class="input-count" type="text" value="X1-A" name="book_name" disabled />

                                        </div>
                                        <div class="input-jumlah">
                                            <label class="label" for="">Status</label>
                                            <select class="input-count" id="status" name="loans_status" disabled>
                                                <option id="loans_status" value="">Status</option>
                                                <option value="2">Perpanjang</option>
                                                <option value="3">Dikembalikan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-content">

                                        <label class="label" for="">Catatan </label>
                                        <textarea class="input alamat" id="alamat" name="description" rows="4" cols="50" placeholder="Masukkan alamat lengkap Anda" disabled required></textarea>
                                    </div>

                                </div>
                                <div class="dua">
                                    <h1 class="subtitle">Formulir Peminjaman</h1>
                                    <div class="input-content">
                                        <label class="label" for="">Judul Buku</label>
                                        <select class="input" id="status" name="fullname" disabled>
                                            <option id="loans_status" value="">judul buku</option>
                                            <option value="2">Gab</option>
                                            <option value="3">Kelvin keleden</option>
                                        </select>
                                    </div>
                                    <div class="count_book">
                                        <div class="input-jumlah">
                                            <label class="label" for="">tersedia</label>
                                            <input class="input-count" type="text" value="2" name="tersedia" reado />

                                        </div>
                                        <div class="input-jumlah">
                                            <label class="label" for="">Jumlah Yang dipinjam</label>
                                            <input class="input-count" type="number" name="total_books" min="1" step="1" required disabled>
                                        </div>
                                    </div>
                                    <div class="count_book">
                                        <div class="input-jumlah">
                                            <label class="label" for="">Tanggal Pengembalian</label>
                                            <input class="input-count" type="date" name="publisher" disabled />
                                        </div>
                                        <div class="input-jumlah">
                                            <label class="label" for="">Kategori</label>
                                            <select class="input-count" id="status" name="fullname" disabled>
                                                <option id="loans_status" value="">Kategori</option>
                                                <option value="2">fiksi</option>
                                                <option value="3">nonfiksi</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="button">
                                <button class="batal " onclick="closeViewPopup()" type="button">Batal</button>
                                <button class="simpan" type="submit">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- popup delete peminjaman -->
                <div id="popup__delete" class="container__popup">
                    <div class="popup_delete">
                        <div class="title_delete">
                            <div class="form-group">
                                <h1>Konfirmasi Hapus</h1>

                            </div>
                        </div>
                        <div class="popup__content">
                            <div class="title_delete">
                                <h3>Apakah anda yakin ingin menghapus Peminjaman Ini?</h3>
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

<script type="text/javascript" src="<?= base_url('js/loans.js') ?>"></script>
<?= $this->endSection() ?>