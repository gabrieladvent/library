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

  <nav class="container-navbar">
    <div>
      <h1>SMP SWASTA KATOLIK SANTA URSULA ENDE</h1>
    </div>


  </nav>
  <main class="container-auth">
    <h1 class="welcome">Welcome</h1>
    <div class="container">
      <div class="auth">
        <div class="title">
          <h1>Login</h1>
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
        text: "<?= session()->getFlashdata('success') ?>",
        duration: 3000,
        gravity: "top", // top or bottom
        position: "right", // left, center, or right
        backgroundColor: "green",
      }).showToast();
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      Toastify({
        text: "<?= session()->getFlashdata('error') ?>",
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor: "red",
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