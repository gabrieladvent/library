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
                            <th class="th-name">Judul Buku</th>
                            <th class="th-name">Penulis</th>
                            <th class="th-tanggal">Tahun Peminjaman</th>
                            <th class="th-tanggal">Tanggal Pengembalian</th>
                            <th class="th-jumlah">Jumlah Buku</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>


                        <tr>
                            <td>1</td>
                            <td>Andrea Hirata</td>
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
                                        <input class="input" type="text" name="fullname" />
                                    </div>
                                    <div class="count_book">
                                        <div class="input-jumlah">
                                            <label class="label" for="">Kelas</label>
                                            <input class="input-count" type="text" value="X1-A" name="book_name" readonly />

                                        </div>
                                        <div class="input-jumlah">
                                            <label class="label" for="">Status</label>
                                            <input class="input-count" type="text" value="Pinjam" name="status_loans" readonly />

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
                                        <input class="input" type="text" name="book_name" />
                                    </div>
                                    <div class="count_book">
                                        <div class="input-jumlah">
                                            <label class="label" for="">tersedia</label>
                                            <input class="input-count" type="text" name="book_name" />

                                        </div>
                                        <div class="input-jumlah">
                                            <label class="label" for="">Jumlah Yang dipinjam</label>
                                            <input class="input-count" type="number" name="total_books" min="1" step="1" required>
                                        </div>
                                    </div>
                                    <div class="input-content">
                                        <label class="label" for="">Tanggal Pengembalian</label>
                                        <input class="input" type="text" name="publisher" />
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
                                        <input class="input" type="text" name="fullname" disabled />
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
                                        <input class="input" type="text" name="book_name" disabled />
                                    </div>
                                    <div class="count_book">
                                        <div class="input-jumlah">
                                            <label class="label" for="">tersedia</label>
                                            <input class="input-count" type="text" name="tersedia" disabled />

                                        </div>
                                        <div class="input-jumlah">
                                            <label class="label" for="">Jumlah Yang dipinjam</label>
                                            <input class="input-count" type="number" name="total_books" min="1" step="1" required disabled>
                                        </div>
                                    </div>
                                    <div class="input-content">
                                        <label class="label" for="">Tanggal Pengembalian</label>
                                        <input class="input" type="text" name="publisher" disabled />
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


z
<?= $this->endSection() ?>