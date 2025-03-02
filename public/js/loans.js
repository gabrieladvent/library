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
      if (response.status === "success") {
        const resp = response.data;

        // Mengisi input lainnya
        $("input[name='class_name']").val(resp.user.class_name);
        $("textarea[name='notes']").val(resp.loan.notes || "");

        // Mengisi dropdown status
        $("#status").val(resp.loan.status);

        $("input[name='available_books']").val(resp.book.available_books);
        $("input[name='book_name']").val(resp.book.book_name);
        $("input[name='book_id']").val(resp.book.id).attr("hidden", true);
        $("input[name='user_id']").val(resp.user.fullname);
        $("input[name='quantity']").val(resp.loan.quantity);
        $("input[name='loan_date']").val(resp.loan.loan_date || "");
        $("input[name='return_date_expected']").val(
          resp.loan.return_date_expected || ""
        );

        // Pastikan form memiliki action yang benar
        $("#popup__lihat form").attr(
          "action",
          `${window.location.origin}/loans/edit?loans=${id}`
        );
        validateQuantity();
        // Pastikan semua input di-disable saat pertama kali dibuka
      } else {
        alert("Gagal mengambil data peminjaman.");
        closePopup();
        closeDeletePopup();
      }
    },
    error: function () {
      alert("Terjadi kesalahan dalam mengambil data.");
      closePopup();
    },
  });
}

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

    fetchAndSetupBookDropdown();
  } else {
    // **Nonaktifkan mode edit & Kembalikan input ke kondisi disabled**
    inputs.forEach((input) => {
      if (input.type !== "checkbox" && input.id !== "enableEdit") {
        if (input.name !== "available_books" && input.name !== "loan_date") {
          input.setAttribute("disabled", "true"); // Kembalikan ke disabled
        }
      }
    });
    submitBtn.setAttribute("disabled", "true"); // Disable tombol submit
  }
}

/*
Fungsi untuk validasi jumlah pinjam

ini fungsi untuk mengecek jika 
dia set value 0 maka akan ada informasi error 
dan jika kita melebih dari ketersediaan maka dia akan error juga
*/
function validateQuantity() {
  const totalBooksInput = document.querySelector(
    "#popup__lihat input[name='quantity']"
  );
  const availableInput = document.querySelector(
    "#popup__lihat input[name='available_books']"
  );

  // Hapus pesan error yang mungkin ada sebelumnya
  let errorMessage = totalBooksInput.nextElementSibling;
  if (!errorMessage || errorMessage.tagName.toLowerCase() !== "div") {
    errorMessage = document.createElement("div");
    errorMessage.style.color = "red";
    errorMessage.style.fontSize = "12px";
    errorMessage.style.marginTop = "5px";
    totalBooksInput.insertAdjacentElement("afterend", errorMessage);
  }

  // Ambil nilai awal
  const availableBooks = parseInt(availableInput.value, 10) || 0;
  const totalBooks = parseInt(totalBooksInput.value, 10) || 0;

  // Tambahkan event listener agar validasi berjalan setelah user mengubah input
  totalBooksInput.addEventListener("input", function () {
    const newTotalBooks = parseInt(totalBooksInput.value, 10) || 0;

    if (newTotalBooks > availableBooks) {
      errorMessage.textContent =
        "Jumlah yang dipinjam melebihi jumlah yang tersedia!";
    } else if (newTotalBooks <= 0) {
      errorMessage.textContent = "Jumlah pinjam harus lebih dari 0!";
    } else {
      errorMessage.textContent = "";
      totalBooksInput.style.borderColor = "";
    }
  });
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
    window.location.href = "/loans/list";
    popup.style.display = "none";

    // **Redirect ke /loans/list setelah popup ditutup**
  }, 300); // Sesuaikan dengan durasi animasi
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

// delete loans function
function Delete(button) {
  const id = button.getAttribute("data-id");
  const bookName = button.getAttribute("data-name");

  const popup = document.getElementById("popup__delete");
  const popupContent = popup.querySelector(".popup_delete");

  popup.querySelector(".title_delete p").textContent = bookName;
  popup.style.display = "flex";
  popup.style.opacity = "1";
  popup.style.visibility = "visible";

  popupContent.style.opacity = "1";
  popupContent.style.transform = "translate(-50%, -50%) scale(1)";

  document.getElementById("confirmDelete").onclick = function () {
    $.ajax({
      url: `${window.location.origin}/loans/delete?loans=${id}`,
      type: "GET",
      dataType: "json",
      success: function (response) {
        closeDeletePopup(); // Tutup popup
        if (response.status === "success") {
          Toastify({
            className: "notif bx bxs-check-circle",
            text: "Data Berhasil di Hapus",
            duration: 3000,
            gravity: "top", // top or bottom
            position: "right", // left, center, or right
            backgroundColor: "#D9FFF0",
            style: {
              marginTop: "60px",
              color: "green",
              borderRadius: "8px",
            },
            escapeHTML: false, // Allow HTML content
          }).showToast();
          setTimeout(() => {
            window.location.href = "/loans/list";
          }, 1000);
        }
      },
      error: function () {
        Toastify({
          className: "notif bx bxs-check-circle",
          text: "Error: Hapus Buku",
          duration: 3000,
          gravity: "top", // top or bottom
          position: "right", // left, center, or right
          backgroundColor: "#FFD9E7",
          style: {
            marginTop: "60px",
            color: "green",
            borderRadius: "8px",
          },
          escapeHTML: false, // Allow HTML content
        }).showToast();
        setTimeout(() => {
          window.location.href = "/loans/list";
        }, 1000);
      },
    });
  };

  document.getElementById("popup__close_delete").onclick = function (e) {
    e.preventDefault();
    closeDeletePopup();
  };
}
function closeDeletePopup() {
  const popup = document.getElementById("popup__delete");
  const popupContent = popup.querySelector(".popup");

  popup.style.opacity = "0";
  popup.style.visibility = "hidden";

  setTimeout(() => {
    popup.style.display = "none";
    popupContent.style.transform = "translate(-50%, -50%) scale(0.8)";
  }, 300);
}

document.addEventListener("DOMContentLoaded", function () {
  // Ambil elemen tombol Batal dan popup
  const batalAdd = document.querySelector(".batal_add");
  const popups = document.getElementById("popuploans");
  const popup = popups.querySelector(".popup_loans");

  if (batalAdd) {
    batalAdd.addEventListener("click", function (e) {
      e.preventDefault();
      popups.style.opacity = "0";
      popups.style.visibility = "hidden";

      window.location.href = "";
      setTimeout(() => {
        popup.style.display = "none";
        popup.style.transform = "translate(-50%, -50%) scale(0.8)";
      }, 300);
    });
  }
});
