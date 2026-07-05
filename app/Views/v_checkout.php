<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4" style="color: #005088;">Detail Pesanan</h5>
                    
                    <?= form_open('buy', ['id' => 'form-checkout', 'class' => 'row g-3']) ?>
                        <?= form_hidden('username', session()->get('username')) ?>
                        <input type="hidden" name="total_harga" id="total_harga_input">

                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">Nama</label>
                            <input type="text" name="nama_tampil" class="form-control bg-light" value="<?= session()->get('username') ?>" readonly>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">Alamat</label>
                            <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukkan alamat lengkap" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">Kelurahan</label>
                            <select name="kelurahan" id="kelurahan" class="form-select"></select>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">Layanan</label>
                            <select name="layanan" id="layanan" class="form-select" disabled>
                                <option value="0">Pilih Kelurahan Terlebih Dahulu</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">Ongkir</label>
                            <input type="number" name="ongkir" id="ongkir" class="form-control bg-light" value="0" readonly>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">Kode Kupon</label>
                            <input type="text" name="kupon_code" id="kupon_code" class="form-control" placeholder="Masukkan kupon (Contoh: HEMAT20)">
                            <div class="form-text small">Tersedia: <span class="badge bg-secondary">HEMAT20</span> <span class="badge bg-secondary">HEMAT30</span> <span class="badge bg-secondary">MEMBER25</span></div>
                        </div>

                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">Buat Pesanan</button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0 sticky-top" style="top: 90px; z-index: 10;">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4" style="color: #005088;">Ringkasan Pesanan</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle small">
                            <thead class="text-muted border-bottom">
                                <tr>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item) : ?>
                                <tr>
                                    <td width="40%"><?= $item['name'] ?></td>
                                    <td>IDR <?= number_format($item['price'], 0, ',', '.') ?></td>
                                    <td class="text-center"><?= $item['qty'] ?></td>
                                    <td class="text-end">IDR <?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold">IDR <?= number_format($subtotal, 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-success">Diskon Kupon <span id="persen_diskon">(0%)</span></span>
                            <span class="text-success fw-bold">- IDR <span id="tampil_diskon">0</span></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-danger">PPN (12%)</span>
                            <span class="text-danger fw-bold">+ IDR <?= number_format($ppn, 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                            <span class="text-danger">Biaya Admin</span>
                            <span class="text-danger fw-bold">+ IDR <?= number_format($biaya_admin, 0, ',', '.') ?></span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="fw-bold d-block">Total</span>
                                <small class="text-muted">(Termasuk Ongkir)</small>
                            </div>
                            <h4 class="fw-bold text-success mb-0">IDR <span id="grand_total">0</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    let ongkir = 0;
    let diskon = 0;
   
    const subtotal_murni = <?= $subtotal ?>;
    const ppn_murni = <?= $ppn ?>;
    const admin_murni = <?= $biaya_admin ?>;

    function formatRupiah(angka) {
        return angka.toLocaleString('id-ID');
    }

    function hitungTotalAkhir() {
        let total = subtotal_murni - diskon + ppn_murni + admin_murni + ongkir;
        $("#grand_total").text(formatRupiah(total));
        $("#total_harga_input").val(total);
    }

    hitungTotalAkhir();

    $("#kupon_code").on('input', function() {
        let code = $(this).val().toUpperCase().trim();
        let persen = 0;

        if (code === 'HEMAT20') { persen = 20; }
        else if (code === 'HEMAT30') { persen = 30; }
        else if (code === 'MEMBER25') { persen = 25; }

        diskon = (persen / 100) * subtotal_murni;
        $("#persen_diskon").text(`(${persen}%)`);
        $("#tampil_diskon").text(formatRupiah(diskon));
        hitungTotalAkhir();
    });

   $('#kelurahan').select2({
        theme: 'bootstrap-5',
        placeholder: 'Cari daerah tujuan...',
        minimumInputLength: 3,
        ajax: {
            url: '<?= base_url('ajax/destinations') ?>',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.results
                };
            }
        }
    });
    $("#kelurahan").on('change', function() {
        let id_kelurahan = $(this).val();
        
        $("#layanan").prop('disabled', false).empty().append('<option>Memuat layanan...</option>');
        
        ongkir = 0;
        hitungTotalAkhir();

        $.ajax({
            url: "<?= site_url('ajax/costs') ?>",
            dataType: "json",
            data: { 
                destination: id_kelurahan
            },
            success: function(data) {
                $("#layanan").empty().append('<option value="0">-- Pilih Layanan Kurir --</option>');
           
                data.forEach(item => {
                    $("#layanan").append(`<option value="${item.cost}">${item.description} (${item.service}) : IDR ${item.cost.toLocaleString('id-ID')} (ETD: ${item.etd} Hari)</option>`);
                });
            },
            error: function() {
                $("#layanan").empty().append('<option value="0">Gagal memuat layanan kurir</option>');
            }
        });
    });

    $("#layanan").on('change', function() {
        ongkir = parseInt($(this).val()) || 0;
        $("#ongkir").val(ongkir);
        hitungTotalAkhir();
    });
});
</script>
<?= $this->endSection() ?>