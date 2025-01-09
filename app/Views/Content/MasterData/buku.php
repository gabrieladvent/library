<?= $this->extend('Layouts/default') ?>

<?= $this->section('content') ?>

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
                                    <td><?= esc($book['created_at'] ?? '') ?></td>
                                    <td>
                                        <img
                                            src="<?= esc($book['cover_img'] ?? '') ?>"
                                            alt="Sampul Buku"
                                            class="book-cover" />
                                    </td>

                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= base_url('buku/lihat/' . $book['id']) ?>" class="btn btn-view">
                                                <i class="bx bx-show"></i> Lihat
                                            </a>
                                            <a href="<?= base_url('buku/edit/' . $book['id']) ?>" class="btn btn-edit">
                                                <i class="bx bx-edit"></i> Edit
                                            </a>
                                            <a href="<?= base_url('buku/delete/' . $book['id']) ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
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
                        <form action="<?= base_url('book/add') ?>" method="post" autocomplete="off">
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
                                                name="author[]" placeholder="Masukkan nama author (Pisahkan dengan koma)"
                                                required />
                                        </div>
                                        <div id="result"></div>
                                    </div>

                                    <div class="input-content">
                                        <label for="fileInput">Pilih Gambar:</label>
                                        <input class="" type="file" id="fileInput" name="image" accept="image/*" required>
                                    </div>


                                </div>
                                <div class="dua">
                                    <div class="input-content">
                                        <label class="label" for="">Penerbit</label>
                                        <input class="input" type="text" name="publisher" />
                                    </div>
                                    <div class="input-content">
                                        <label class="label" for="">Tahun Terbit</label>
                                        <input class="input" type="text" name="year_published" />
                                    </div>
                                    <div class="count_book">
                                        <div class="input-jumlah">
                                            <label class="label" for="">Total copy</label>
                                            <input class="input-count" type="number" id="quantity" name="total_copies" min="1" max="1000" step="1" required>

                                        </div>
                                        <div class="input-jumlah">
                                            <label class="label" for="">jumlah Buku</label>
                                            <input class="input-count" type="number" id="quantity" name="total_books" min="1" max="1000" step="1" required>
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
            </div>
        </div>
    </div>
</div>
<script>
    const authorInput = document.getElementById('author-input');
    const resultDiv = document.getElementById('result');

    authorInput.addEventListener('input', function() {
        // Mengambil nilai input dan memisahkan berdasarkan koma
        const authors = this.value.split(',').map(author => author.trim());

        // Membuat format output
        let output = '';
        authors.forEach(author => {
            if (author) {
                output += `author[]: ${author}\n`;
            }
        });


    });
</script>

<?= $this->endSection() ?>