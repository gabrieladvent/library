<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
    .container {
        display: flex;
        text-align: center;
        justify-content: center;
        flex-direction: column;

    }

    .text {
        padding-right: 150px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: color 0.5s infinite;
    }

    @keyframes color {
        0% {
            color: white;
        }

        100% {
            color: red;
        }
    }

    .button {
        color: white;
        margin-top: 30px;
        margin-left: 43.5rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 346px;
        height: 92px;
        flex-shrink: 0;
        border-radius: 15px;
        background: #FF7474;
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
        cursor: pointer;
        text-decoration: none;
        font-size: 24px;
    }

    .button:hover {
        background-color: red;
    }
</style>

<body>
    <div class="container">
        <div>
            <img src="<?= base_url("img/errors.png") ?>" alt="">
        </div>
        <div class="text">
            <p>silakan klik tombol di bawah ini untuk menuju halaman utama</p>
        </div>
        <a href="<?= base_url("home/dashboard") ?>" class="button">

            Halaman Utama

        </a>
    </div>
</body>

</html>