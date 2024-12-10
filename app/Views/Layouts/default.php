<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?= $this->include('components/navbar') ?>

    <!-- Konten Halaman -->
    <?= $this->renderSection('content') ?>

    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Script untuk menampilkan notifikasi Toastify -->
    <script type="text/javascript">
        <?php if (session()->getFlashdata('success')): ?>
            Toastify({
                text: "<?= session()->getFlashdata('success') ?>",
                duration: 3000,
                close: true,
                gravity: "top", // "top" or "bottom"
                position: "right", // "left", "center", "right"
                backgroundColor: "green"
            }).showToast();
        <?php elseif (session()->getFlashdata('error')): ?>
            Toastify({
                text: "<?= session()->getFlashdata('error') ?>",
                duration: 3000,
                close: true,
                gravity: "top", // "top" or "bottom"
                position: "right", // "left", "center", "right"
                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc3a0)"
            }).showToast();
        <?php endif; ?>
    </script>

</body>

</html>