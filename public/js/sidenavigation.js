const sidebar = document.getElementById("sidebar");
const navLinks = document.querySelectorAll(".nav a");
const dropdownBtns = document.querySelectorAll(".dropdown-btn"); // Menangani tombol dropdown secara terpisah
const activePage = window.location.pathname;
const subMenuItems = document.querySelectorAll(".sub-menu-item"); // Menangani item submenu

// Fungsi untuk membuka dan menutup submenu
function toggleSubMenu(button) {
  const subMenu = button.nextElementSibling; // Mendapatkan submenu terkait
  const isShowing = subMenu.classList.contains("show");
  closeAllSubMenu(); // Tutup semua submenu
  if (!isShowing) {
    subMenu.classList.add("show");
    button.classList.add("rotate");
  }
}

// Menonaktifkan submenu
function closeAllSubMenu() {
  const submenus = sidebar.querySelectorAll(".sub-menu.show");

  submenus.forEach((submenu) => {
    submenu.classList.remove("show"); // Menutup submenu
    submenu.previousElementSibling.classList.remove("rotate"); // Mengubah ikon
  });
}

document.addEventListener("DOMContentLoaded", () => {
  // Memuat status link aktif dari localStorage
  const activeLinkId = localStorage.getItem("activeLink");
  if (activeLinkId) {
    const activeLink = document.getElementById(activeLinkId);
    if (activeLink) {
      activeLink.classList.add("active");
    }
  }

  // Memuat status sub-menu yang terbuka dari localStorage
  if (localStorage.getItem("masterDataOpen") === "true") {
    document.querySelector("#master-data-menu").classList.add("visible");
  }

  // Event listener untuk setiap link di sidebar
  navLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      // Hapus kelas 'active' dari semua link di dalam grup navigasi
      navLinks.forEach((nav) => nav.classList.remove("active"));

      // Tambahkan kelas 'active' hanya pada elemen yang diklik
      link.classList.add("active");

      // Simpan status link aktif di localStorage
      localStorage.setItem("activeLink", link.getAttribute("id"));

      // Cek jika link yang diklik berada dalam submenu yang terbuka
      const isInsideSubMenu = link.closest(".sub-menu");
      if (!isInsideSubMenu) {
        closeAllSubMenu(); // Tutup semua submenu jika klik di luar submenu
      }
    });
  });

  // Event listener untuk membuka/tutup sub-menu Master Data
  dropdownBtns.forEach((button) => {
    button.addEventListener("click", () => {
      const subMenu = button.nextElementSibling;
      subMenu.classList.toggle("visible");
      // Simpan status sub-menu di localStorage
      if (subMenu.id === "master-data-menu") {
        localStorage.setItem(
          "masterDataOpen",
          subMenu.classList.contains("visible")
        );
      } else if (subMenu.id === "laporan-menu") {
        localStorage.setItem(
          "laporanOpen",
          subMenu.classList.contains("visible")
        );
      }
    });
  });
});

// Event listener untuk klik di luar sidebar untuk menutup submenu
document.addEventListener("click", (event) => {
  const isClickInsideSidebar = sidebar.contains(event.target);
  const isClickOnDropdownBtn = event.target.closest(".dropdown-btn"); // Cek apakah klik pada dropdown button
  const isClickOnSubMenuItem = event.target.closest(".sub-menu-item"); // Cek apakah klik pada item submenu

  // Jika klik di luar sidebar dan bukan pada dropdown button atau item submenu, tutup semua submenu
  if (!isClickInsideSidebar && !isClickOnDropdownBtn && !isClickOnSubMenuItem) {
    closeAllSubMenu();
  }
});

// Event listener untuk sub-menu item untuk mencegah penutupan submenu saat item diklik
subMenuItems.forEach((item) => {
  item.addEventListener("click", (event) => {
    event.stopPropagation(); // Jangan tutup submenu jika item diklik
  });
});
