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
      console.log("Response:", response); // Debugging
      if (response.success) {
        const book = response.data.book_detail;
        console.log(book.cover_img);

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
        $("#total_copies").val(book.total_copies || "");
        $("#total_books").val(book.total_books || "");
        $("#description").val(book.description || "");

       
      } else {
        alert(response.message || "Gagal mengambil data buku");
        closePopup();
      }

      $('#formDetailUser').attr('data-book-id', id);
      const actionUrl = `${window.location.origin}/book/edit?books=${encodeURIComponent(id)}`;
      console.log('Action URL:', actionUrl);
      $('#formDetailUser').attr('action', actionUrl);
      $('#formDetailUser').attr('method', 'POST');
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
// // Edit mode toggle
// $("#editMode").change(function () {
//     const isChecked = $(this).is(":checked");
//     // Enable or disable fields based on checkbox state
//     $(
//       "#category_name, #fullname, #isbn, #author, #cover_image, #publisher, #year_published, #total_copies, #total_books, #description"
//     ).prop("disabled", !isChecked);
//   });
  


// Close popup event listener
document.getElementById("popup__close").addEventListener("click", (event) => {
    event.preventDefault(); // Prevent page reload if the button is an <a href="#">
    closePopup(); // Call closePopup function
  });
  
  
// Function to close the popup
function closePopup() {
  const popup = document.getElementById("popup__lihat"); // Target main popup element
  popup.style.opacity = "0";
  popup.style.visibility = "hidden";
  setTimeout(() => (popup.style.display = "none"), 300); // Delay according to CSS transition duration
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
