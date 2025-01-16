<?= $this->extend('Layouts/default') ?>

<?= $this->section('content') ?>
<div class="dashboard-container">

    <div class="view-container" id="loans-container">
        <h1><?= $count_newborrower ?></h1>
        <p>Peminjaman Baru</p>
        <div class="container_img">
            <img src="<?= base_url("img/Peminjaman.png") ?>" alt="" />
        </div>
    </div>
    <div class="view-container" id="book-container">
        <h1><?= $count_book ?></h1>
        <p>Buku Tersedia</p>
        <div class="container_img">
            <img src="<?= base_url("img/Buku.png") ?>" alt="" />
        </div>

    </div>
    <div class="view-container" id="users-container">
        <h1><?= $count_users['count_user'] ?></h1>

        <p>jumlah Anggota</p>
        <div class="container_img">
            <img src="<?= base_url("img/Anggota.png") ?>" alt="" />
        </div>

    </div>
</div>

<?= $this->endSection() ?>