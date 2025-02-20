function viewDetailClass(button) {
  const id = button.getAttribute("data-id");
  const popup = document.getElementById("popup_viewClass");

  // Show popup
  popup.classList.add("active");
  popup.style.display = "flex";
  popup.style.opacity = "1";
  popup.style.visibility = "visible";
  popup.querySelector(".popup_viewclass").style.opacity = "1";
  popup.querySelector(".popup_viewclass").style.transform =
    "translate(-50%, -50%) scale(1)";

  $.ajax({
    url: `${window.location.origin}/class/list?classes=${id}`,
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.success) {
        const classdetial = response.data;

        // Populate form fields
        // Add fields based on your form structure
        $("#class_name").val(classdetial.class_name);

        // ... add other fields

        // Set form attributes
        $("#formDetailUser").attr("data-user-id", id);

        const actionUrl = `${window.location.origin}/class/edit?class=${id}`;
        $("#formDetailUser").attr("action", actionUrl);
      } else {
        alert("Failed to fetch user data");
        closePopup();
        closePopupClass();
      }
    },
  });
}
function toggleEditClass(checkbox) {
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

//
//
//

function closePopup() {
  const popup = document.getElementById("popup_viewClass"); // Elemen popup yang benar
  const checkbox = document.getElementById("enableEditClass"); // Checkbox yang benar
  const inputs = document.querySelectorAll(
    "#formDetailUser input, #formDetailUser textarea, #formDetailUser select"
  );

  if (checkbox) {
    checkbox.checked = false;
    inputs.forEach((input) => {
      if (input.id !== "enableEditClass") {
        input.setAttribute("disabled", true);
        input.classList.remove("editable");
      }
    });
  }

  popup.style.opacity = "0";
  popup.style.visibility = "hidden";

  setTimeout(() => {
    popup.style.display = "none"; // Pastikan popup tersembunyi
  }, 300);
}

//
//
//
//   button batal

function closePopupClass() {
  const popup = document.getElementById("popup_viewClass");
  const popupContent = popup.querySelector(".popup_viewclass");

  popup.style.opacity = "0";
  popup.style.visibility = "hidden";

  setTimeout(() => {
    popup.style.display = "none";
    popupContent.style.transform = "translate(-50%, -50%) scale(0.8)";
  }, 300);
}

document.addEventListener("DOMContentLoaded", function () {
  const closeDeleteBtn = document.getElementById("popup__close_delete");
  if (closeDeleteBtn) {
    closeDeleteBtn.addEventListener("click", function (e) {
      e.preventDefault();
      closePopupClass();
    });
  }
});

function DeleteClass(button) {
  const id_class = button.getAttribute("data-id");
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
      url: `${window.location.origin}/class/delete?classes=${id_class}`,
      type: "GET",
      dataType: "json",
      success: function (response) {
        closeDeletePopup(); // Tutup popup
        // Redirect ke halaman yang sesuai dengan tipe
        window.location.href = `/class/all`;
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
          window.location.href = `/class/all`;
        }
      },
      error: function (xhr, status, error) {
        closeDeletePopup();
        window.location.href = `/class/all`;
      },
    });
  };
}

function closeDeletePopup() {
  const popup = document.getElementById("popup__delete");
  const popupContent = popup.querySelector(".popup_delete");

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
  const popups = document.getElementById("popup_addclass");
  const popup = popups.querySelector(".popup_AddClas");

  if (batalAdd) {
    batalAdd.addEventListener("click", function (e) {
      e.preventDefault();
      popups.style.opacity = "0";
      popups.style.visibility = "hidden";

      window.location.href = "";
      setTimeout(() => {
        popups.style.display = "none";
        popup.style.transform = "translate(-50%, -50%) scale(0.8)";
      }, 300);
    });
  }
});
