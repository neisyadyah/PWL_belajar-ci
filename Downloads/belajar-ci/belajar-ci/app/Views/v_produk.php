<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">

<?php foreach ($produk as $p): ?>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5><?= $p['nama_produk'] ?></h5>
                <p><?= $p['harga'] ?></p>
            </div>
        </div>
    </div>

<?php endforeach; ?>

</div>

<?= $this->endSection() ?>