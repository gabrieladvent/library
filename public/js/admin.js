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
  
  function validationPassword(){
    password = document.getElementById("password")
    konfrmPassword = document.getElementById("konfiPassword")
    
    if(password != konfrmPassword){
      alert("password dan konfirmasi password error")
    }
  }