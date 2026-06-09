<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashData('success')) : ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashData('failed')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('failed') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
    Tambah Data
</button>
<a class="btn btn-success" target="_blank" href="<?= base_url()?>produk/download">
    Download Data
</a>
<table class="table datatable">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($products)) : ?>
            <?php foreach ($products as $index => $produk) : ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($produk['nama']) ?></td>
                    <td><?= esc($produk['harga']) ?></td>
                    <td><?= esc($produk['jumlah']) ?></td>
                    <td>
                        <?php if (!empty($produk['foto']) && file_exists(FCPATH . 'img/' . $produk['foto'])) : ?>
                            <img src="<?= base_url('img/' . $produk['foto']) ?>" width="100">
                        <?php endif; ?>
                    </td>
                    <td>
                        aksi
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6" class="text-center">Data tidak ada</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?= $this->include('produk/modal_add') ?>
<?= $this->include('produk/modal_edit') ?>

<?= $this->endSection() ?>