function viewDetailAnggota(button) {
  const id = button.getAttribute("data-id");
  const popup = document.getElementById("popup__lihat");

  // Show popup
  popup.classList.add("active");
  popup.style.display = "flex";
  popup.style.opacity = "1";
  popup.style.visibility = "visible";
  popup.querySelector(".popup").style.opacity = "1";
  popup.querySelector(".popup").style.transform =
    "translate(-50%, -50%) scale(1)";

  $.ajax({
    url: `${window.location.origin}/user/detail?users=${id}`,
    type: "GET",
    dataType: "json",

    success: function (response) {
      if (response.success) {
        const user = response.data.user_detail;
        const loans = response.data.user_loans;

        // Populate form fields

        // Add fields based on your form structure
        // Set nilai dropdown berdasarkan class_id
        $("#class_id_popup").val(user.class_id);
        // Set dropdown nilai kelas berdasarkan class_id
        $("#class_id").val(user.class_id);
        $("#username").val(user.username || user.email);
        $("#fullname").val(user.fullname);
        $("#gender").val(user.gender);
        $("#religion").val(user.religion);
        $("#place_birth").val(user.place_birth);
        $("#date_birth").val(user.date_birth);
        $("#phone").val(user.phone);
        $("#identification").val(user.identification);

        $("#address").val(user.address);
        // ... add other fields

        // Set form attributes
        $("#formDetailUser").attr("data-user-id", id);
        const actionUrl = `${window.location.origin}/user/edit?users=${id}&type=User`;
        $("#formDetailUser").attr("action", actionUrl);
      } else {
        alert("Failed to fetch user data");
        closePopup();
      }
    },
  });
}

function validationPasswordAnggota() {
  password = document.getElementById("password");
  konfrmPassword = document.getElementById("konfiPassword");

  if (password != konfrmPassword) {
    alert("password dan konfirmasi password error");
  }
}

function toggleEditAnggota(checkbox) {
  const inputs = document.querySelectorAll(
    "#formDetailUser input, #formDetailUser textarea, #formDetailUser select"
  );
  const submitBtn = document.querySelector(
    '#formDetailUser button[type="submit"]'
  );
  const passwordFields = document.querySelector("#input_password");

  if (checkbox.checked) {
    inputs.forEach((input) => {
      if (input.id !== "enableEdit") {
        input.removeAttribute("disabled");
      }
    });

    submitBtn.style.display = "block"; // Tampilkan tombol submit
    if (passwordFields) {
      passwordFields.style.display = "block"; // Tampilkan input password
    }
  } else {
    inputs.forEach((input) => {
      if (input.id !== "enableEdit") {
        input.setAttribute("disabled", true);
      }
    });

    submitBtn.style.display = "none"; // Sembunyikan tombol submit
    if (passwordFields) {
      passwordFields.style.display = "none"; // Sembunyikan input password
    }
  }
}

// Function to close the popup lihat detail
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

document.getElementById("popup__close").addEventListener("click", (event) => {
  // Prevent page reload if the button is an <a href="#">

  closePopup(); // Call closePopup function
});

// delete data
// delete button
function DeleteAnggota(button) {
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
      url: `${window.location.origin}/user/delete?users=${id}`,
      type: "GET",
      dataType: "json",
      success: function (response) {
        closeDeletePopup(); // Tutup popup
        if (response.status === "success") {
          // Redirect ke halaman dashboard
          Toastify({
            className: "notif bx bxs-check-circle",
            text: " Data Berhasil di Hapus",
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
            window.location.href = "/user/list/Anggota";
          }, 1000);
        } else {
          // Redirect ke halaman dashboard dengan pesan error
          window.location.href = "/user/list/Anggota    ";
        }
      },
      error: function (xhr, status, error) {
        closeDeletePopup(); // Tutup popup
        // Redirect ke halaman dashboard dengan pesan error
        window.location.href = "/user/list/nggota";
        Toastify({
          className: "notif bx bxs-check-circle",
          text: " Data Berhasil di Hapus",
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
