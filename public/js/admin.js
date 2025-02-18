function viewDetailAdmin(button) {
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
        $("#username").val(user.username || user.email);
        $("#fullname").val(user.fullname);
        $("#gender").val(user.gender);
        $("#religion").val(user.religion);
        $("#place_birth_user").val(user.place_birth);
        $("#date_birth").val(user.date_birth);
        $("#phone").val(user.phone);
        $("#identification").val(user.identification);

        $("#address").val(user.address);
        // ... add other fields

        // Set form attributes
        $("#formDetailUser").attr("data-user-id", id);

        const actionUrl = `${window.location.origin}/user/edit?users=${id}`;
        $("#formDetailUser").attr("action", actionUrl);
      } else {
        alert("Failed to fetch user data");
        closePopup();
        closeDeletePopup();
      }
    },
  });
}

function validationPasswordAdmin() {
  let password = document.getElementById("password").value;
  let konfrmPassword = document.getElementById("konfiPassword").value;

  if (password !== konfrmPassword) {
    alert("Password dan konfirmasi password tidak cocok!");
    return false;
  }
}

function toggleEditAdmin(checkbox) {
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
        input.classList.add("editable");
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
        input.classList.remove("editable");
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
        input.classList.remove("editable");
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
  event.preventDefault();
  closePopup(); // Call closePopup function
});

// delete data
// delete button
function DeleteAdmin(button) {
  const id_delete = button.getAttribute("data-id");
  const userType = button.getAttribute("data-type"); // Ambil tipe pengguna (Admin atau Anggota)
  const userName = button.getAttribute("data-name");

  const popup = document.getElementById("popup__delete");
  const popupContent = popup.querySelector(".popup_delete");

  popup.querySelector(".title_delete p").textContent = userName;
  popup.style.display = "flex";
  popup.style.opacity = "1";
  popup.style.visibility = "visible";

  popupContent.style.opacity = "1";
  popupContent.style.transform = "translate(-50%, -50%) scale(1)";

  document.getElementById("confirmDelete").onclick = function () {
    $.ajax({
      url: `${window.location.origin}/user/delete?users=${id_delete}`,
      type: "GET",
      dataType: "json",
      success: function (response) {
        closeDeletePopup(); // Tutup popup
        // Redirect ke halaman yang sesuai dengan tipe
        window.location.href = `/user/list/${userType}`;
        if (response.status === "success") {
          Toastify({
            className: "notif bx bxs-check-circle",
            text: " Data Berhasil di Hapus",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#D9FFF0",
            style: {
              marginTop: "60px",
              color: "green",
              borderRadius: "8px",
            },
            escapeHTML: false,
          }).showToast();
        } else {
          // Redirect dengan pesan error
          window.location.href = `/user/list/${userType}`;
        }
      },
      error: function (xhr, status, error) {
        closeDeletePopup(); // Tutup popup
        // Redirect dengan pesan error
        window.location.href = `/user/list/${userType}`;
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
