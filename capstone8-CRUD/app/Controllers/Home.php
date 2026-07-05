<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Home extends BaseController
{
    protected $productModel;

    function __construct(){
        $this->productModel = new ProductModel();
    }
    
    public function index(): string
    {
        $products = $this->productModel->findAll();
        $data['products'] = $products;

        return view('v_home', $data);
    }

    public function faq()
    {
        return view('v_faq');
    }

    public function profile()
    {
        return view('v_profile');
    }
}