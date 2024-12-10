<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Page</title>
  <!-- Toastify CSS -->

  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

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
              <label for="">Password</label>
              <input id="password" name="password" type="text" />
            </div>
            <div class="masuk">
              <label for=""><a href="">forgot Password</a></label>
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


</body>

</html>