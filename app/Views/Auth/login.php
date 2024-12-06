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

  <?= view('components/navbar') ?>

  <main class="container-auth">
    <h1 class="welcome">Welcome</h1>
    <div class="container">
      <div class="auth">
        <div class="title">
          <h1>Login</h1>
        </div>
        <div class="input">
          <form action="" autocomplete="off" method="POST">
            <div class="input-satu">
              <label for="">Name</label>
              <input id="name" type="text" />
            </div>
            <div id="input-pw" class="input-satu">
              <label for="">Password</label>
              <input id="password" type="text" />
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


    <!-- jika sudah terhubung ke database -->
  <script>
    document.querySelector(".button-masuk").addEventListener("click", (e) => {
      e.preventDefault(); // Mencegah form submit

      const nameInput = document.getElementById("name").value;
      const passwordInput = document.getElementById("password").value;

      if (!nameInput || !passwordInput) {
        // Jika ada input kosong, tampilkan pesan error
        Toastify({
          text: "Login gagal! Name atau Password tidak boleh kosong.",
          duration: 3000,
          gravity: "top",
          position: "right",
          backgroundColor: "#f44336",
          stopOnFocus: true,
        }).showToast();
        return;
      }

      // Kirim data ke backend
      fetch('login/proses', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            name: nameInput,
            password: passwordInput
          }),
        })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === 'success') {
            Toastify({
              text: data.message,
              duration: 3000,
              gravity: "top",
              position: "right",
              backgroundColor: "#4caf50",
              stopOnFocus: true,
            }).showToast();

            // Redirect ke halaman lain jika login berhasil
            setTimeout(() => {
              window.location.href = "/dashboard";
            }, 3000);
          } else {
            Toastify({
              text: data.message,
              duration: 3000,
              gravity: "top",
              position: "right",
              backgroundColor: "#f44336",
              stopOnFocus: true,
            }).showToast();
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          Toastify({
            text: "Terjadi kesalahan pada server.",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#f44336",
            stopOnFocus: true,
          }).showToast();
        });
    });
  </script>


  <!-- TESTING TOASTIFY -->
  <script>
    document.querySelector(".button-masuk").addEventListener("click", (e) => {
      e.preventDefault(); // Mencegah form submit
      console.log("button BERHASIL DI CLICK")
      // Ambil elemen input
      const nameInput = document.getElementById("name")
      const passwordInput = document.getElementById("password");

      // Data dummy
      const dummyName = "admin";
      const dummyPassword = "123456";

      // Validasi input
      if (!nameInput.value || !passwordInput.value) {
        // Jika ada input kosong, tampilkan pesan error
        Toastify({
          text: "Login gagal! Name atau Password tidak boleh kosong.",
          duration: 3000,
          gravity: "top",
          position: "right",
          backgroundColor: "#f44336",
          stopOnFocus: true,
        }).showToast();
      } else if (nameInput.value === dummyName && passwordInput.value === dummyPassword) {
        // Jika login berhasil
        Toastify({
          text: "Login berhasil! Selamat datang.",
          duration: 3000,
          gravity: "top",
          position: "right",
          backgroundColor: "#4caf50",
          stopOnFocus: true,
        }).showToast();
      } else {
        // Jika login gagal
        Toastify({
          text: "Login gagal! Name atau Password salah.",
          duration: 3000,
          gravity: "top",
          position: "right",
          backgroundColor: "#f44336",
          stopOnFocus: true,
        }).showToast();
      }
    });
  </script>
  <!-- END TESTING TOASTIFY -->

</body>

</html>