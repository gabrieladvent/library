<?= $this->extend('Layouts/default') ?>

<?php $this->section('content');
$encrypter = \Config\Services::encrypter(); ?>

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
                                                <i class="bx bx-show"></i> Lihat
                                            </button>
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
                                        <input class="input" type="text" name="year_published" />
                                    </div>
                                    <div class="count_book">
                                        <div class="input-jumlah">
                                            <label class="label" for="">Total copy</label>
                                            <input class="input-count" type="number" name="total_copies" min="1" max="1000" step="1" required>

                                        </div>
                                        <div class="input-jumlah">
                                            <label class="label" for="">jumlah Buku</label>
                                            <input class="input-count" type="number" name="total_books" min="1" max="1000" step="1" required>
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
                            <h1>Lihat Detail</h1>
                            <a href="#" id="popup__close" class="popup-close">&times;</a>
                        </div>
                        <form id="formDetailUser" method="get" autocomplete="off">
                            <?= csrf_field() ?>

                            <div class="container__input">
                                <div class="satu">
                                    <div class="input-content">
                                        <label class="label">Jenis Buku</label>
                                        <input class="input" id="category_name" readonly>

                                    </div>
                                    <div class="input-content">
                                        <label class="label">Nama Buku</label>
                                        <input class="input" type="text" id="fullname" readonly />
                                    </div>
                                    <div class="input-content">
                                        <label class="label">ISBN</label>
                                        <input class="input" type="text" id="isbn" readonly />
                                    </div>
                                    <div class="input-content">
                                        <label class="label" for="">Author</label>
                                        <input class="input" type="text" id="author" readonly />
                                    </div>
                                    <div class="input-content">
                                        <label class="label" for="">Cover Image</label>
                                        <img src="" id="cover_img" alt="cover image" width="500">
                                    </div>
                                </div>
                                <div class="dua">
                                    <div class=" input-content">
                                        <label class="label">Penebit</label>
                                        <input class="input" type="text" id="publisher" readonly />
                                    </div>

                                    <div class="input-content">
                                        <label class="label" for="">Tahun Terbit</label>
                                        <input class="input" type="text" id="year_published" readonly />
                                    </div>
                                    <div class="count_book">
                                        <div class="input-jumlah">
                                            <label class="label" for="">Total copy</label>
                                            <input class="input-count" type="number" id="total_copies" readonly>
                                        </div>
                                        <div class="input-jumlah">
                                            <label class="label" for="">jumlah Buku</label>
                                            <input class="input-count" type="number" id="total_books" min="1" max="1000" step="1" required>
                                        </div>
                                    </div>

                                    <div class="input-content">
                                        <label class="label" for="">Deskripsi Buku </label>
                                        <textarea class="input alamat" id="description" rows="4" cols="50" placeholder="Masukkan alamat lengkap Anda" readonly></textarea>

                                        <div>
                                        </div>
                                    </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function viewDetail(button) {
        const id = button.getAttribute('data-id');
        const cover_img_id = document.getElementById('cover_img');

        // Tampilkan popup
        const popup = document.getElementById('popup__lihat');
        document.getElementById('popup__lihat').classList.add('active');

        popup.style.display = 'flex';
        popup.style.opacity = "1"
        popup.style.visibility = 'visible';
        popup.querySelector('.popup').style.opacity = "1"
        popup.querySelector('.popup').style.transform = 'translate(-50%, -50%) scale(1)'

        $.ajax({
            url: `${window.location.origin}/book/detail/${id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response); // Debugging
                if (response.success) {
                    const book = response.data.book_detail;
                    console.log(book.cover_img);
                    

                    // Isi form dengan data buku

                    $('#category_name').val(book.category_name || '');
                    $('#fullname').val(book.book_name || '');
                    $('#isbn').val(book.isbn || '');
                    $('#author').val(book.author || '');
                    cover_img_id.src = `${window.location.origin}/${encodeURIComponent(book.cover_img)}`;
                    $('#publisher').val(book.publisher || '');
                    $('#year_published').val(book.year_published || '');
                    $('#total_copies').val(book.total_copies || '');
                    $('#total_books').val(book.total_books || '');
                    $('#description').val(book.description || '');
                    // Tambahkan data lainnya di sini...
                } else {
                    alert(response.message || 'Gagal mengambil data buku');
                    closePopup();
                }
            },

        });
    }

    document.getElementById('popup__close').addEventListener('click', (event) => {
        event.preventDefault(); // Mencegah reload halaman jika tombol adalah <a href="#">
        closePopup(); // Panggil fungsi closePopup
    });

    function closePopup() {
        const popup = document.getElementById('popup__lihat'); // Target elemen popup utama
        popup.style.opacity = '0';
        popup.style.visibility = 'hidden';
        setTimeout(() => popup.style.display = 'none', 300); // Delay sesuai durasi transisi CSS
    }
</script>

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