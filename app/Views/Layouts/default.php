<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="<?= base_url("css/style.table.css") ?>" />
    <link rel="stylesheet" href="<?= base_url("css/style.popup.css") ?>" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('css/style.dashboard.css') ?>" />
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

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
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

</html>