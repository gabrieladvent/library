document.addEventListener("DOMContentLoaded", () => {
    // Menentukan elemen-elemen yang perlu dikelola
    const sidebarLinks = document.querySelectorAll(".nav a");
    const subMenus = document.querySelectorAll(".sub-menu");
    const masterDataToggle = document.querySelector("#master-data-toggle");
    const laporanToggle = document.querySelector("#laporan-toggle");

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
    if (localStorage.getItem("laporanOpen") === "true") {
        document.querySelector("#laporan-menu").classList.add("visible");
    }

    // Event listener untuk setiap link di sidebar
    sidebarLinks.forEach(link => {
        link.addEventListener("click", (e) => {
            // Hapus kelas 'active' dari semua link di dalam grup navigasi
            sidebarLinks.forEach(nav => nav.classList.remove("active"));

            // Tambahkan kelas 'active' hanya pada elemen yang diklik
            link.classList.add("active");

            // Simpan status link aktif di localStorage
            localStorage.setItem("activeLink", link.getAttribute("id"));
        });
    });

    // Event listener untuk membuka/tutup sub-menu Master Data
    masterDataToggle.addEventListener("click", () => {
        const menu = document.querySelector("#master-data-menu");
        menu.classList.toggle("visible");

        // Simpan status sub-menu Master Data di localStorage
        localStorage.setItem("masterDataOpen", menu.classList.contains("visible"));
    });

    // Event listener untuk membuka/tutup sub-menu Laporan
    laporanToggle.addEventListener("click", () => {
        const menu = document.querySelector("#laporan-menu");
        menu.classList.toggle("visible");

        // Simpan status sub-menu Laporan di localStorage
        localStorage.setItem("laporanOpen", menu.classList.contains("visible"));
    });
});
