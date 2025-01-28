<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="<?= base_url("css/style.table.css") ?>" />
    <link rel="stylesheet" href="<?= base_url("css/style.popup.css") ?>" />
    <link rel="stylesheet" href="<?= base_url("css/style.daful.css") ?>" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('css/style.dashboard.css') ?>" />


    <!-- javascript -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Document</title>
</head>

<body>

    <!-- navbar -->
    <?= view('Components/navbar') ?>
    <!-- end navbar -->

    <div class="container">
        <!-- sidebar -->
        <?= view('Components/sideNavigation') ?>
        <!-- endsidebar -->

        <!-- Menampilkan konten dari halaman lain -->
        <?= $this->renderSection('content') ?>

    </div>

    <script>
        // Cek apakah ada pesan sukses atau error
        <?php if (session()->getFlashdata('success')): ?>
            Toastify({
                className: "notif bx bxs-check-circle",
                text: " <?= session()->getFlashdata('success') ?>",
                duration: 3000,
                gravity: "top", // top or bottom
                position: "right", // left, center, or right
                backgroundColor: "#D9FFF0",
                style: {
                    marginTop: "60px",
                    color: "green",
                    borderRadius: "8px"
                },
                escapeHTML: false // Allow HTML content
            }).showToast();
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            Toastify({
                className: "notif bx bxs-x-circle",
                text: " <?= session()->getFlashdata('error') ?>",
                duration: 3000,
                gravity: "top",
                position: "right",
                style: {

                    color: "red",
                }
            }).showToast();
        <?php endif; ?>
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const auth = document.querySelector(".user");
            const subMenu = auth.nextElementSibling;

            auth.addEventListener("click", () => {
                subMenu.classList.toggle("visible")
            })
        })
    </script>
</body>
<script type="text/javascript" src="<?= base_url('js/books.js') ?>"></script>

</html>