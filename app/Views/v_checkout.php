<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-6">
        <table class="table">
  <thead>
      <tr>
          <th scope="col">Nama</th>
          <th scope="col">Harga</th>
          <th scope="col">Jumlah</th>
          <th scope="col">Sub Total</th>
      </tr>
  </thead>
  <tbody>
      <?php 
      if (!empty($items)) :
          foreach ($items as $index => $item) :
      ?>
              <tr>
                  <td><?= $item['name'] ?></td>
                  <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                  <td><?= $item['qty'] ?></td>
                  <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
              </tr>
      <?php
          endforeach;
      endif;
      ?>
      <tr>
          <td colspan="2"></td>
          <td>Subtotal</td>
          <td><?= number_to_currency($total, 'IDR') ?></td>
      </tr>
      <tr>
        <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control']) ?>
          <td colspan="2"></td>
          <td>Total</td>
          <td><span id="total"><?= number_to_currency($total, 'IDR') ?></span></td>
      </tr>
  </tbody>
</table>
    </div>
    <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-control']) ?>
    <div class="col-lg-6">
        <table class="table">
  <thead>
      <tr>
          <th scope="col">Nama</th>
          <th scope="col">Harga</th>
          <th scope="col">Jumlah</th>
          <th scope="col">Sub Total</th>
      </tr>
  </thead>
  <tbody>
      <?php 
      if (!empty($items)) :
          foreach ($items as $index => $item) :
      ?>
              <tr>
                  <td><?= $item['name'] ?></td>
                  <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                  <td><?= $item['qty'] ?></td>
                  <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
              </tr>
      <?php
          endforeach;
      endif;
      ?>
      <tr>
          <td colspan="2"></td>
          <td>Subtotal</td>
          <td><?= number_to_currency($total, 'IDR') ?></td>
      </tr>
      <tr>
          <td colspan="2"></td>
          <td>Total</td>
          <td><span id="total"><?= number_to_currency($total, 'IDR') ?></span></td>
      </tr>
  </tbody>
</table>
    </div>
</div>
<?= $this->section('script') ?>
<script>
$(document).ready(function() {
	$('#kelurahan').select2({
	    placeholder: 'Cari daerah tujuan',
	    minimumInputLength: 3, 
	});
});

ajax: {
    url: '<?= site_url('ajax/destinations') ?>',
    dataType: 'json',
    delay: 300,
    data: function(params) {
        return {
            q: params.term
        };
    },
    processResults: function(data) {
        return data;
    },
    cache: true
}

let ongkir = 0;
let subtotal = <?= $total ?>;
hitungTotal();

function hitungTotal() {
    let total = subtotal + ongkir;

    $("#ongkir").val(ongkir);
    $("#total").text(`IDR ${total.toLocaleString('id-ID')}`);
    $("#total_harga").val(total);
}
$("#kelurahan").on('change', function () {
    let id_kelurahan = $(this).val();

    $("#layanan").empty();
    ongkir = 0;
    hitungTotal(); 

    console.log(id_kelurahan);
});
$.ajax({
    url: "<?= site_url('ajax/costs') ?>", 
    dataType: "json",
    data: {
        destination: id_kelurahan
    },
    success: function (data) { 
        data.forEach(function (item) {
            $("#layanan").append(
                $('<option>', {
                    value: item.cost,
                    text: `${item.description} (${item.service}) : estimasi ${item.etd}`
                })
            );
        });
    }
});
$("#layanan").on('change', function() {
    ongkir = parseInt($(this).val());
    hitungTotal();
}); 
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>