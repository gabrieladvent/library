// Perbaikan fungsi viewDetailLoans
function viewDetailLoans(button) {
  const id = button.getAttribute("data-id");
  const popup = document.getElementById("popup__lihat");

  // Tampilkan popup
  popup.classList.add("active");
  popup.style.display = "flex";
  popup.style.opacity = "1";
  popup.style.visibility = "visible";
  popup.querySelector(".popup").style.opacity = "1";
  popup.querySelector(".popup").style.transform =
    "translate(-50%, -50%) scale(1)";

  // Reset form dan status edit
  const checkbox = document.getElementById("enableEdit");
  if (checkbox) {
    checkbox.checked = false;
  }

  // Ambil data dari API
  $.ajax({
    url: `${window.location.origin}/loans/detail?loans=${id}`,
    type: "GET",
    dataType: "json",
    success: function (response) {
      console.log("Response:", response);

      if (response.status === "success") {
        const loan = response.data.loan;
        const book = response.data.book;
        const user = response.data.user;

        // Mengisi select user
        $("#memberSelect").html(
          `<option value="${user.id}" selected>${user.fullname}</option>`
        );
        $("#memberSelect").trigger("change");

        // Mengisi input lainnya
        $("input[name='class_name']").val(user.class_name);
        $("textarea[name='notes']").val(loan.notes || "");

        // Mengisi dropdown status
        $("#status").val(loan.status);

        // Mengisi select book
        $("#bookSelectedit").html(
          `<option value="${book.id}" selected>${book.book_name}</option>`
        );
        $("#bookSelectedit").trigger("change");

        $("input[name='available_books']").val(book.available_books);
        $("input[name='book_name']").val(book.book_name);
        $("input[name='user_id']").val(user.fullname);
        $("input[name='quantity']").val(loan.quantity);
        $("input[name='loan_date']").val(loan.loan_date || "");
        $("input[name='return_date_expected']").val(
          loan.return_date_expected || ""
        );

        // Pastikan form memiliki action yang benar
        $("#popup__lihat form").attr(
          "action",
          `${window.location.origin}/loans/edit?loans=${id}`
        );

        // Pastikan semua input di-disable saat pertama kali dibuka
        disableEditMode();
      } else {
        alert("Gagal mengambil data peminjaman.");
        closePopup();
      }
    },
    error: function () {
      alert("Terjadi kesalahan dalam mengambil data.");
      closePopup();
    },
  });
}

// Perbaikan fungsi toggleEdit
function toggleEdit(checkbox) {
  // Ambil form yang ada pada popup edit
  const form = document.querySelector("#popup__lihat form");
  const inputs = form.querySelectorAll("input, textarea, select");
  const submitBtn = form.querySelector("button[type='submit']");

  if (checkbox.checked) {
    // Aktifkan mode edit
    inputs.forEach((input) => {
      if (input.type !== "checkbox" && input.id !== "enableEdit") {
        // Jangan aktifkan field yang memang harus read-only
        if (input.name !== "available_books" && input.name !== "loan_date") {
          input.removeAttribute("disabled");
        }
      }
    });
    submitBtn.removeAttribute("disabled");

    // Fetch data untuk dropdown user dan book
    fetchAndSetupUserDropdown();
    fetchAndSetupBookDropdown();
  } else {
    // Nonaktifkan mode edit
    disableEditMode();
  }
}

// Fungsi untuk validasi jumlah pinjam
function validateQuantity() {
  const totalBooksInput = document.querySelector(
    "#popup__lihat input[name='quantity']"
  );
  const availableInput = document.querySelector(
    "#popup__lihat input[name='available_books']"
  );

  // Hapus pesan error yang mungkin ada sebelumnya
  let errorMessage = totalBooksInput.nextElementSibling;
  if (
    errorMessage &&
    errorMessage.style &&
    errorMessage.style.color === "red"
  ) {
    errorMessage.remove();
  }

  // Buat elemen pesan error baru jika belum ada
  errorMessage = document.createElement("div");
  errorMessage.style.color = "red";
  errorMessage.style.fontSize = "12px";
  errorMessage.style.marginTop = "5px";
  totalBooksInput.insertAdjacentElement("afterend", errorMessage);

  // Tambahkan event listener untuk validasi
  totalBooksInput.addEventListener("input", function () {
    const availableBooks = parseInt(availableInput.value, 10) || 0;
    const totalBooks = parseInt(totalBooksInput.value, 10) || 0;

    if (totalBooks > availableBooks) {
      errorMessage.textContent =
        "Jumlah yang dipinjam melebihi jumlah yang tersedia!";
      totalBooksInput.style.borderColor = "red";
    } else if (totalBooks <= 0) {
      errorMessage.textContent = "Jumlah pinjam harus lebih dari 0!";
      totalBooksInput.style.borderColor = "red";
    } else {
      errorMessage.textContent = "";
      totalBooksInput.style.borderColor = "";
    }
  });

  // Validasi awal
  totalBooksInput.dispatchEvent(new Event("input"));
}

// Perbaikan fungsi closePopup
function closePopup() {
  const popup = document.getElementById("popup__lihat");

  // Reset form dan Select2
  if ($("#memberSelect").data("select2")) {
    $("#memberSelect").select2("destroy");
  }
  if ($("#bookSelectedit").data("select2")) {
    $("#bookSelectedit").select2("destroy");
  }

  // Reset checkbox edit
  const checkbox = document.getElementById("enableEdit");
  if (checkbox) {
    checkbox.checked = false;
  }

  // Sembunyikan popup dengan animasi
  popup.style.opacity = "0";
  popup.style.visibility = "hidden";

  setTimeout(() => {
    popup.style.display = "none";
  }, 300);
}

// Event listener untuk tombol batal
document.addEventListener("DOMContentLoaded", function () {
  const batalBtn = document.querySelector("#popup__lihat .batal_add");
  if (batalBtn) {
    batalBtn.addEventListener("click", function (e) {
      e.preventDefault();
      closePopup();
    });
  }

  // Listener untuk tombol close
  const closeBtn = document.getElementById("popup__close");
  if (closeBtn) {
    closeBtn.addEventListener("click", function (e) {
      e.preventDefault();
      closePopup();
    });
  }

  // Tambahkan event listener untuk validasi jumlah pada form tambah peminjaman
  const addFormQuantityInput = document.querySelector(
    "#popuploans input[name='quantity']"
  );
  const addFormAvailableInput = document.querySelector(
    "#popuploans input[name='available_books']"
  );

  if (addFormQuantityInput && addFormAvailableInput) {
    const errorMessage = document.createElement("div");
    errorMessage.style.color = "red";
    errorMessage.style.fontSize = "12px";
    errorMessage.style.marginTop = "5px";
    addFormQuantityInput.insertAdjacentElement("afterend", errorMessage);

    addFormQuantityInput.addEventListener("input", function () {
      const availableBooks = parseInt(addFormAvailableInput.value, 10) || 0;
      const totalBooks = parseInt(addFormQuantityInput.value, 10) || 0;

      if (totalBooks > availableBooks) {
        errorMessage.textContent =
          "Jumlah yang dipinjam melebihi jumlah yang tersedia!";
        addFormQuantityInput.style.borderColor = "red";
      } else if (totalBooks <= 0) {
        errorMessage.textContent = "Jumlah pinjam harus lebih dari 0!";
        addFormQuantityInput.style.borderColor = "red";
      } else {
        errorMessage.textContent = "";
        addFormQuantityInput.style.borderColor = "";
      }
    });
  }
});

// Tambahkan fungsi untuk inisialisasi tanggal pada form tambah
document.addEventListener("DOMContentLoaded", function () {
  // Set tanggal peminjaman dan pengembalian default pada form tambah
  const today = new Date();
  const nextWeek = new Date();
  nextWeek.setDate(today.getDate() + 7);

  const formatDate = (date) => {
    return date.toISOString().split("T")[0];
  };

  const loanDateInput = document.querySelector(
    "#popuploans input[name='loan_date']"
  );
  const returnDateInput = document.querySelector(
    "#popuploans input[name='return_date_expected']"
  );

  if (loanDateInput) {
    loanDateInput.value = formatDate(today);
  }

  if (returnDateInput) {
    returnDateInput.value = formatDate(nextWeek);
  }
});
//
//

// dropdwon search and add data fucntion
document.addEventListener("DOMContentLoaded", function () {
  const totalBooksInput = document.querySelector("input[name='quantity']");
  const availableInput = document.querySelector(
    "input[name='available_books']"
  );

  const errorMessage = document.createElement("div");
  errorMessage.style.color = "red";
  errorMessage.style.fontSize = "12px";
  errorMessage.style.marginTop = "5px";
  totalBooksInput.insertAdjacentElement("afterend", errorMessage);

  totalBooksInput.addEventListener("input", function () {
    const availableBooks = parseInt(availableInput.value, 10) || 0;
    const totalBooks = parseInt(totalBooksInput.value, 10) || 0;

    if (totalBooks > availableBooks) {
      errorMessage.textContent =
        "Jumlah yang dipinjam melebihi jumlah yang tersedia!";
      totalBooksInput.style.borderColor = "red";
    } else {
      errorMessage.textContent = "";
      totalBooksInput.style.borderColor = "";
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const today = new Date();
  const nextWeek = new Date();
  nextWeek.setDate(today.getDate() + 7);

  const formatDate = (date) => {
    return date.toISOString().split("T")[0];
  };

  document.querySelector("input[name='loan_date']").value = formatDate(today);
  document.querySelector("input[name='return_date_expected']").value =
    formatDate(nextWeek);
});

// dropdown search data user and books
// User Dropdown Implementation
document.addEventListener("DOMContentLoaded", function () {
  // Referensi elemen
  const memberSelect = document.querySelector("select[name='user_id']");
  const classInput = document.querySelector("input[name='class_name']");
  const bookSelect = document.querySelector("select[name='book_id']");
  const availableInput = document.querySelector(
    "input[name='available_books']"
  );

  // Fungsi matcher yang akan digunakan untuk kedua dropdown
  function customMatcher(params, data) {
    // Jika tidak ada term pencarian
    if ($.trim(params.term) === "") {
      return data;
    }

    // Ambil teks pencarian dan data
    const searchText = params.term.toLowerCase();
    const originalText = data.text.toLowerCase();

    // Lakukan pencarian
    if (originalText.indexOf(searchText) > -1) {
      return data;
    }

    // Jika tidak cocok
    return null;
  }

  // Konfigurasi dasar Select2
  const select2Config = {
    allowClear: true,
    matcher: customMatcher,
    language: {
      noResults: function () {
        return "Tidak ada hasil yang ditemukan";
      },
      searching: function () {
        return "Mencari...";
      },
    },
  };

  // Inisialisasi Select2 untuk user
  $(memberSelect).select2({
    ...select2Config,
    placeholder: "Pilih Anggota",
  });

  // Inisialisasi Select2 untuk buku
  $(bookSelect).select2({
    ...select2Config,
    placeholder: "Pilih Buku",
  });

  // Fetch data user
  fetch(`${window.location.origin}/user/all-user`)
    .then((response) => response.json())
    .then((data) => {
      $(memberSelect).empty();
      $(memberSelect).append(new Option("Pilih Anggota", ""));

      data.data.forEach((member) => {
        $(memberSelect).append(new Option(member.fullname, member.id));
      });

      // Trigger change untuk memastikan Select2 terupdate
      $(memberSelect).trigger("change");
    })
    .catch((err) => console.error("Error mengambil data anggota: ", err));

  // even handler user
  $(memberSelect).on("change", function () {
    let memberId = $(this).val();
    if (memberId) {
      fetch(`${window.location.origin}/user/class?users=${memberId}`)
        .then((response) => response.json())
        .then((data) => {
          classInput.value = data.data.class_name || "";
        })
        .catch((err) => console.error("Error mengambil data kelas: ", err));
    } else {
      classInput.value = "";
    }
  });

  // Fetch data buku
  fetch(`${window.location.origin}/book/all-books`)
    .then((response) => response.json())
    .then((data) => {
      $(bookSelect).empty();
      $(bookSelect).append(new Option("Pilih Buku", ""));

      data.data.forEach((book) => {
        const option = new Option(book.book_name, book.id);
        if (book.available_books <= 0) {
          option.disabled = true;
        }
        $(bookSelect).append(option);
      });

      // Trigger change untuk memastikan Select2 terupdate
      $(bookSelect).trigger("change");
    })
    .catch((err) => console.error("Error mengambil data buku: ", err));

  // Event handler untuk buku
  $(bookSelect).on("change", function () {
    let bookId = $(this).val();
    if (bookId) {
      fetch(`${window.location.origin}/book/available?books=${bookId}`)
        .then((response) => response.json())
        .then((data) => {
          availableInput.value = data.data.available_books || "";
        })
        .catch((err) =>
          console.error("Error mengambil data buku tersedia: ", err)
        );
    } else {
      availableInput.value = "";
    }
  });
});
