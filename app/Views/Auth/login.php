<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Page</title>
  <!-- Toastify CSS -->

  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link
    href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
    rel="stylesheet" />

  <link rel="stylesheet" href="<?= base_url('css/style.login.css') ?>">

</head>

<body>


  <? $id_user = session('id_user'); ?>

  <main class="container">
    <div class="container_description">
      <div class="container_logo">
        <img src="<?= base_url('img/logo.png') ?>" alt="">
        <h1>SMP KATOLIK SANTA URSULA ENDE ADMIN PERPUSTAKAAN</h1>
      </div>
      <div class="container_info">
        <img src="<?= base_url('img/bg.jpg') ?>" alt="">
        <div class="info">
          <h1>Selamat datang</h1>
          <p>di Admin Perpustakaan SMP Swasta Katolik Santa Ursula Ende!
            Tempat di mana pengelolaan koleksi buku, data anggota, dan aktivitas perpustakaan dilakukan dengan rapi dan efisien untuk mendukung kemajuan literasi siswa. </p>
        </div>
      </div>
    </div>
    <div class="container_auth">
      <div class="auth">
        <div class="title">
          <h1>Admin Perpustakan</h1>
          <h1>Silakan Masuk</h1>
        </div>
        <div class="input">
          <form action="<?= base_url('login/proses') ?>" autocomplete="off" method="POST">
            <div class="input-satu">
              <label for="">Name</label>
              <input id="name" name="username" type="text" />
            </div>
            <div id="input-pw" class="input-satu">
              <label for="password">Password</label>
              <div class="password-wrapper">
                <input id="password" name="password" type="password" autocomplete="off" />
                <i id="toggle-password" class="bx bx-show"></i>
              </div>
            </div>
            <div class="information">
              <p>Pastikan user name dan password
                terisi dengan benar</p>
            </div>
            <div class="masuk">
              <button class="button-masuk">masuk</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>


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
        backgroundColor: "#FFD9E7",
        style: {
          color: "red",
        }
      }).showToast();
    <?php endif; ?>
  </script>


  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const passwordInput = document.getElementById("password");
      const togglePassword = document.getElementById("toggle-password");

      // Toggle visibility
      togglePassword.addEventListener("click", () => {
        if (passwordInput.type === "password") {
          passwordInput.type = "text";
          togglePassword.classList.replace("bx-show", "bx-hide"); // Ganti ikon
        } else {
          passwordInput.type = "password";
          togglePassword.classList.replace("bx-hide", "bx-show"); // Ganti ikon
        }
      });
    });
  </script>


</body>

</html>