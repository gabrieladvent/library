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
      href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"
    />

    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

* {
  padding: 0;
  margin: 0;
  font-family: Poppins;
}

.container-navbar {
  background-color: #00c777;
  color: #383838;
  width: auto;
  height: 2rem;
  display: flex;
  justify-content: space-between;
  padding: 1rem;
  align-items: center;
}

.container-auth {
  /* background-color: #00c777; */
  display: flex;
  justify-content: center;
  flex-direction: column;
}
.welcome {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px;
}

.container {
  /* background-color: #00c777; */
  display: flex;
  justify-content: center;
}

.auth {
  display: flex;
  justify-content: center;
  /* background-color: antiquewhite; */
  background: var(--secondary-color, #e4eae4);
  box-shadow: 1px 2px 12px 2px rgba(27, 56, 2, 0.25);
  flex-direction: column;
  padding: 1rem;
  width: 500px;
}

.auth .input form {
  display: flex;
  flex-direction: column;
  margin: 2rem;
  gap: 30px;
}
.title {
  color: var(--font-logo, #383838);
  font-family: Poppins;
  font-size: 12px;
  font-style: normal;
  font-weight: 600;
  line-height: normal;
  /* background-color: aquamarine; */
  display: flex;
  justify-content: center;
  width: auto;
}
.input-satu {
  display: flex;
  flex-direction: column;
}

.input-satu input {
  width: 421px;
  height: 41px;
  flex-shrink: 0;
  border-radius: 8px;
  border: 1px solid #000;
  background: #fff;
  padding: 0px 15px;
}

.input-satu label {
  color: #838383;
  font-family: Poppins;
  font-size: 16px;
  font-style: normal;
  font-weight: 600;
  line-height: normal;
}

.masuk {
  display: flex;
  flex-direction: column;
  /* background-color: #00c777; */
  justify-content: center;
  align-items: center;
  gap: 18px;
}

.masuk .button-masuk {
  /* justify-content: center; */
  border-radius: 8px;
  background: var(--navbar, #00c777);
  width: 152px;
  height: 55px;
  flex-shrink: 0;
  padding: 2px;
  color: var(--font-logo, #383838);
  font-family: Poppins;
  font-size: 24px;
  font-style: normal;
  font-weight: 600;
  line-height: normal;
  border: none;
  margin-bottom: 18px;
  cursor: pointer;
  /* transition: background-color 0.3s ease; */
}
.button-masuk:hover {
  background-color: #0ae48d; /* Warna hijau lebih gelap saat di-hover */
}

.masuk label a {
  color: #838383;
  font-family: Poppins;
  font-size: 12px;
  font-style: normal;
  font-weight: 600;
  line-height: normal;
  text-decoration: none;
}

.masuk label a:hover {
  color: #f52727;
}

    </style>
  </head>

  <body>
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
      src="https://cdn.jsdelivr.net/npm/toastify-js"
    ></script>
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
