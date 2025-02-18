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
      console.log(response);
      if (response.success) {
        const classdetial = response.data;

        // Populate form fields
        // Add fields based on your form structure
        $("#class_name").val(classdetial.class_name);

        // ... add other fields

        // Set form attributes
        $("#formDetailUser").attr("data-user-id", id);

        const actionUrl = `${window.location.origin}/class/edit?classes=${id}`;
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
        input.classList.add("editable");
      }
    });
    submitBtn.style.display = "block";

    // Ambil ID dari form yang sudah diset saat viewDetai
  } else {
    // Nonaktifkan mode edit
    inputs.forEach((input) => {
      if (input.id !== "enableEdit") {
        input.setAttribute("disabled", true);
        input.classList.remove("editable");
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
