function viewDetailCategory(button) {
  const id = button.getAttribute("data-id");
  const popup = document.getElementById("popup_viewCategory");

  // Show popup
  popup.classList.add("active");
  popup.style.display = "flex";
  popup.style.opacity = "1";
  popup.style.visibility = "visible";
  popup.querySelector(".popup_viewcategory").style.opacity = "1";
  popup.querySelector(".popup_viewcategory").style.transform =
    "translate(-50%, -50%) scale(1)";

  $.ajax({
    url: `${window.location.origin}/category/list?category=${id}`,
    type: "GET",
    dataType: "json",
    success: function (response) {
      console.log(response);

      if (response.success) {
        const categoridetail = response.data;

        // Populate form fields
        // Add fields based on your form structure
        $("#category").val(categoridetail.category_name);
        $("#description").val(categoridetail.description);

        // ... add other fields

        // Set form attributes
        $("#formDetailUser").attr("data-user-id", id);

        const actionUrl = `${window.location.origin}/category/edit?category=${id}`;
        $("#formDetailUser").attr("action", actionUrl);
      } else {
        alert("Failed to fetch user data");
        closePopup();
        closePopupClass();
      }
    },
  });
}

// combo box edit
function toggleEditCategory(checkbox) {
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

function closePopupCategory() {
  const popup = document.getElementById("popup_viewCategory");
  const popupContent = popup.querySelector(".popup_viewcategory");

  popup.style.opacity = "0";
  popup.style.visibility = "hidden";

  setTimeout(() => {
    popup.style.display = "none";
    popupContent.style.transform = "translate(-50%, -50%) scale(0.8)";
  }, 300);
}

// delete cateogory function
function DeleteCategory(button) {
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
      url: `${window.location.origin}/category/delete?category=${id_class}`,
      type: "GET",
      dataType: "json",
      success: function (response) {
        closeDeletePopup(); // Tutup popup
        if (response.status === "success") {
          Toastify({
            className: "notif bx bxs-check-circle",
            text: "Data Berhasil di Hapus",
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

          // Tunda redirect hingga notifikasi selesai tampil
          setTimeout(function () {
            window.location.href = ""; // Ganti dengan URL tujuan yang diinginkan
          }, 3000);
        }
      },
      error: function (xhr, status, error) {
        closeDeletePopup();
        Toastify({
          className: "notif bx bxs-x-circle",
          text: "Terjadi kesalahan saat menghapus data",
          duration: 3000,
          gravity: "top",
          position: "right",
          backgroundColor: "#FFE8E8",
          style: {
            marginTop: "60px",
            color: "red",
            borderRadius: "8px",
          },
          escapeHTML: false,
        }).showToast();

        // Tunda redirect hingga notifikasi selesai tampil
        setTimeout(function () {
          window.location.href = ""; // Ganti dengan URL error atau halaman yang diinginkan
        }, 5000);
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

// tombol batal add
document.addEventListener("DOMContentLoaded", function () {
  // Ambil elemen tombol Batal dan popup
  const batalAdd = document.querySelector(".batal_add");
  const popups = document.getElementById("popup_addcategory");
  const popup = popups.querySelector(".popup_AddCategory");

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
