<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Page</title>
  <!-- Toastify CSS -->
  <link
    rel="stylesheet"
    type="text/css"
    href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
  <link rel="stylesheet" href="<?= base_url('css/style.login.css') ?>">
</head>

<body>

  <?= view('components/navbar') ?>

  <main class="container-auth">
    <h1 class="welcome">Welcome</h1>
    <div class="container">
      <div class="auth">
        <div class="title">
          <h1>Login</h1>
        </div>
        <div class="input">
          <form action="" autocomplete="off">
            <div class="input-satu">
              <label for="">Name</label>
              <input type="text" />
            </div>
            <div class="input-satu">
              <label for="">Password</label>
              <input type="text" />
            </div>
          </form>
          <div class="masuk">
            <label for=""><a href="">forgot Password</a></label>
            <button class="button-masuk">masuk</button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Toastify JS -->
  <script
    type="text/javascript"
    src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <script>
    document.querySelector(".button-masuk").addEventListener("click", (e) => {
      e.preventDefault(); // Mencegah form submit

      const nameInput = document.querySelector(
        "input[type='text']:first-child"
      );
      const passwordInput = document.querySelector(
        "input[type='text']:nth-child(2)"
      );

      if (!nameInput.value || !passwordInput.value) {
        // Tampilkan pesan error menggunakan Toastify
        Toastify({
          text: "Login gagal! Name atau Password tidak boleh kosong.",
          duration: 3000, // Durasi 3 detik
          gravity: "top", // Lokasi di atas
          position: "right", // Lokasi di kanan
          backgroundColor: "#f44336", // Warna merah untuk error
          stopOnFocus: true, // Hentikan jika diklik
        }).showToast();
      }
    });
  </script>
</body>

</html>