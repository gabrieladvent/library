<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        <?= $this->renderSection('content') ?>

    </div>

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