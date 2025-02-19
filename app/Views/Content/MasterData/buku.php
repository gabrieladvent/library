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
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Tahun Terbit</th>
                            <th>Tanggal Ditambahkan</th>
                            <th>Sampull</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($books)): ?>
                            <?php foreach ($books as $index => $book): ?>

                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($book['book_name']) ?></td>
                                    <td><?= esc($book['author']) ?></td>
                                    <td><?= esc($book['publisher'] ?? '') ?></td>
                                    <td><?= esc($book['year_published'] ?? '') ?></td>
                                    <td><?= $book['created_at']  ?></td>
                                    <td>
                                        <img
                                            src="<?= base_url($book['cover_img'] ?? '') ?>"
                                            alt="Sampul Buku"
                                            class="book-cover" />
                                    </td>

                                    <td>
                                        <div class="action-buttons">
                                            <!-- Ubah button view menjadi: -->
                                            <button onclick="viewDetail(this)" class="btn btn-view" data-id="<?= $book['id'] ?>">
                                                <i class="bx bx-edit"></i> Kelolah
                                            </button>
                                            <button class="btn btn-edit" onclick="Delete(this)" data-id="<?= $book['id'] ?>" data-name="<?= $book['book_name'] ?>">
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
                        <form action="<?= base_url('book/add') ?>" method="post" autocomplete="off" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="container__input">
                                <div class="satu">
                                    <div class="input-content">
                                        <select class="input" id="category_id" name="category_id" required>
                                            <option value="">Pilih Jenis Buku</option>
                                            <option value="1">1. Fiksi</option>
                                            <option value="2">2. Novel</option>
                                            <option value="3">3. Sains</option>
                                        </select>
                                    </div>
                                    <div class="input-content">
                                        <label class="label" for="">Judul Buku</label>
                                        <input class="input" type="text" name="book_name" />
                                    </div>
                                    <div class="input-content">
                                        <label class="label" for="">isbn</label>
                                        <input class="input" type="text" name="isbn" />
                                    </div>
                                    <div class="content-author">
                                        <label class="label" for="author">Authors (Pisahkan dengan koma)</label>
                                        <div class="author" id="author-container">
                                            <input class="input-author" type="text" id="author-input"
                                                name="author[]" placeholder="Masukkan nama author"
                                                required />
                                        </div>
                                        <div id="result"></div>
                                    </div>


                                    <div class="input-content">
                                        <label for="fileInput">Pilih Gambar:</label>
                                        <input class="" type="file" id="fileInput" name="cover_img" accept="image/*" required>
                                    </div>


                                </div>
                                <div class="dua">
                                    <div class="input-content">
                                        <label class="label" for="">Penerbit</label>
                                        <input class="input" type="text" name="publisher" />
                                    </div>
                                    <div class="input-content">
                                        <label class="label" for="">Tahun Terbit</label>
                                        <input class="input" type="number" name="year_published" min="1999" max="2025" />
                                    </div>
                                    <div class="count_book">
                                        <div class="input-jumlah">
                                            <label class="label" for="">Total copy</label>
                                            <input class="input-count" type="number" name="total_copies" min="1" step="1" required>

                                        </div>
                                        <div class="input-jumlah">
                                            <label class="label" for="">jumlah Buku</label>
                                            <input class="input-count" type="number" name="total_books" min="1" step="1" required>
                                        </div>
                                    </div>
                                    <div class="input-content">
                                        <label class="label" for="">Deskripsi Buku </label>
                                        <textarea class="input alamat" id="alamat" name="description" rows="4" cols="50" placeholder="Masukkan alamat lengkap Anda" required></textarea>

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
                        <form id="formDetailUser" method="POST" autocomplete="off" enctype="multipart/form-data">
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

<script type="text/javascript" src="<?= base_url('js/books.js') ?>"></script>



<?= $this->endSection() ?>