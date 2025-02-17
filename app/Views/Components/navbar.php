<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/style.navbar.css') ?>">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>

    <nav class="container-navbar">
        <div class="container_logo">
            <img src="<?= base_url('img/logo.png') ?>" alt="">
            <h1>SMP KATOLIK SANTA URSULA ENDE </h1>
        </div>
        <div>
            <a href="javascript:void(0);" class="user">
                <h1><?= $user['username'] ?? $user['email'] ?></h1>
                <i class="bx bx-chevron-down"></i>
            </a>
            <div class="sub-link">
                <a href="<?= base_url('home/logout') ?>">Logout</a>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const auth = document.querySelector(".user");
            const subMenu = document.querySelector(".sub-link");

            auth.addEventListener("click", () => {

            });
        });
    </script>

</body>

</html>