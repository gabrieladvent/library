<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Document' ?></title>

</head>

<body>
    <?= $this->extend('layouts/default') ?>

    <? $id_user = session('id_user'); ?>
    <?= $this->section('content') ?>

    <h1>
        <p>Ini <?= $user['username'] ?></p>
    </h1>

    <a href="<?= base_url('home/logout') ?>" class="btn-logout">Logout</a>
    <?= $this->endSection() ?>


</body>

</html>