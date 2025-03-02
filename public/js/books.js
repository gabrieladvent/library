// Function to view book details
function viewDetail(button) {
  const id = button.getAttribute("data-id");
  const cover_img_id = document.getElementById("cover_img_view");

  // Show popup
  const popup = document.getElementById("popup__lihat");

  popup.classList.add("active");
  popup.style.display = "flex";
  popup.style.opacity = "1";
  popup.style.visibility = "visible";
  popup.querySelector(".popup").style.opacity = "1";
  popup.querySelector(".popup").style.transition = "all .5s .1s;";
  popup.querySelector(".popup").style.transform =
    "translate(-50%, -50%) scale(1)";

  $.ajax({
    url: `${window.location.origin}/book/detail/${id}`,
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.success) {
        const book = response.data.book_detail;

        // Fill form with book data
        $("#category_name").val(book.category_id || "");
        $("#fullname").val(book.book_name || "");
        $("#isbn").val(book.isbn || "");
        $("#author").val(book.author || "");
        cover_img_id.src = `${window.location.origin}/${encodeURIComponent(
          book.cover_img
        )}`;
        $("#publisher").val(book.publisher || "");
        $("#year_published").val(book.year_published || "");
        $("#total_copies").val(book.total_copies || 0);
        $("#total_books").val(book.total_books || 0);
        $("#available_books").val(book.available_books || 0);
        $("#description").val(book.description || "");
      } else {
        alert(response.message || "Gagal mengambil data buku");
        closePopup();
      }

      $("#formDetailUser").attr("data-book-id", id);
      const actionUrl = `${window.location.origin}/book/edit?books=${id}`;
      $("#formDetailUser").attr("action", actionUrl);
      $("#formDetailUser").attr("method", "POST");
    },
  });
}

// edit toggle
function toggleEdit(checkbox) {
  // Ambil semua input di dalam form
  const inputs = document.querySelectorAll(
    "#formDetailUser input, #formDetailUser textarea, #formDetailUser select"
  );
  const submitBtn = document.querySelector(
    '#formDetailUser button[type="submit"]'
  );

  if (checkbox.checked) {
    // Aktifkan mode edit
    inputs.forEach((input) => {
      if (input.id !== "enableEdit") {
        // Jangan ubah checkbox
        input.removeAttribute("disabled");
      }
    });
    submitBtn.style.display = "block";

    // Ambil ID dari form yang sudah diset saat viewDetai
  } else {
    // Nonaktifkan mode edit
    inputs.forEach((input) => {
      if (input.id !== "enableEdit") {
        input.setAttribute("disabled", true);
      }
    });
    submitBtn.style.display = "none";
  }
}

// delete button
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
      url: `${window.location.origin}/book/delete?books=${id}`,
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
            window.location.href = "/book/dashboard";
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
          window.location.href = "/book/dashboard";
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

// Tambahkan event listener terpisah untuk tombol close
document.addEventListener("DOMContentLoaded", function () {
  const closeDeleteBtn = document.getElementById("popup__close_delete");
  if (closeDeleteBtn) {
    closeDeleteBtn.addEventListener("click", function (e) {
      e.preventDefault();
      closeDeletePopup();
    });
  }
});

// Function to close the popup
function closePopup() {
  const popup = document.getElementById("popup__lihat"); // Target main popup element
  const checkbox = document.getElementById("enableEdit");
  const inputs = document.querySelectorAll(
    "#formDetailUser input, #formDetailUser textarea, #formDetailUser select"
  );
  if (checkbox) {
    checkbox.checked = false;
    inputs.forEach((input) => {
      if (input.id !== "enableEdit") {
        input.setAttribute("disabled", true);
      }
    });
  }
  popup.style.opacity = "0";
  popup.style.visibility = "hidden";
  // popupContent.style.transform = "translate(-50%, -50%) scale(1)";
  // Delay according to CSS transition duration
}

// Cover image preview
document
  .getElementById("cover_image")
  .addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById("cover_img_view").src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });

// Author input handling
const authorInput = document.getElementById("author-input");
const resultDiv = document.getElementById("result");

authorInput.addEventListener("input", function () {
  // Get input value and split by comma
  const authors = this.value.split(",").map((author) => author.trim());

  // Create output format
  let output = "";
  authors.forEach((author) => {
    if (author) {
      output += `author[]: ${author}\n`;
    }
  });
  // You can display the output somewhere if needed
  console.log(output); // For debugging purposes
});

// Close popup event listener
document.getElementById("popup__close").addEventListener("click", (event) => {
  // Prevent page reload if the button is an <a href="#">

  closePopup(); // Call closePopup function
});

// tombol batal
// tombol batal add
document.addEventListener("DOMContentLoaded", function () {
  // Ambil elemen tombol Batal dan popup
  const batalAdd = document.querySelector(".batal_add");
  const popups = document.getElementById("popup");
  const popup = popups.querySelector(".popup");

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

function closeViewPopup() {
  const popup = document.getElementById("popup__lihat");
  const popupContent = popup.querySelector(".popup");

  popup.style.opacity = "0";
  popup.style.visibility = "hidden";

  setTimeout(() => {
    popup.style.display = "none";
    popupContent.style.transform = "translate(-50%, -50%) scale(0.8)";
  }, 300);
}
