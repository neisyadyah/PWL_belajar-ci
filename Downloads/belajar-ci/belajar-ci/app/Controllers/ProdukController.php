<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;

class ProdukController extends BaseController
{
    public function index()
    {
        $model = new ProdukModel();

        $data = [
            'produk' => $model->findAll()
        ];

        return view('v_produk', $data);
    }
}
