<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\CLI\CLI;
class TambahKolomTransaksiUas extends Migration
{
   public function up()
    {
        $db = \Config\Database::connect();

        if ($db->fieldExists('ppn', 'transaction')) {
            CLI::write("Kolom 'ppn' sudah ada di tabel 'transaction'. Migrasi dilewati.", 'yellow');
            return;
        }

        $fields = [
            'ppn'          => ['type' => 'DOUBLE', 'null' => true, 'after' => 'ongkir'],
            'biaya_admin'  => ['type' => 'DOUBLE', 'null' => true, 'after' => 'ppn'],
            'kupon_code'   => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'after' => 'biaya_admin'],
            'diskon_kupon' => ['type' => 'DOUBLE', 'null' => true, 'after' => 'kupon_code'],
        ];

        $this->forge->addColumn('transaction', $fields);
    }
    public function down()
    {
        $db = \Config\Database::connect();

        if (!$db->fieldExists('ppn', 'transaction')) {
            \CodeIgniter\CLI\CLI::write("Kolom-kolom baru tidak ditemukan di tabel 'transaction'. Rollback dilewati.", 'yellow');
            return;
        }

        $this->forge->dropColumn('transaction', ['ppn', 'biaya_admin', 'kupon_code', 'diskon_kupon']);
    }
}