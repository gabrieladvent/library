<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/style.navbar.css') ?>">
    <link
        href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
        rel="stylesheet" />
</head>

<body>

    <nav class="container-navbar">
        <div>
            <h1>SMP SWASTA KATOLIK SANTA URSULA ENDE</h1>
        </div>
        <div>
            <a href="javascript:void(0);" class="user">
                <h1><?= $user['username'] ?></h1>
                <i class="bx bx-chevron-down"></i>
            </a>
            <a href="<?= base_url('home/logout')  ?>" class="sub-link hiden">
                <div class="a">
                    <p>Logout</p>
                </div>
            </a>
        </div>
    </nav>
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