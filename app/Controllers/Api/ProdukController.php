<?php

namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductModel;

class ProdukController extends ResourceController
{
    protected $model;  
    private $token;

    function __construct()
    { 
        $this->model = new ProductModel(); 
        $this->token = env('MY_API_KEY');
    }

    private function authenticate()
    {
        $header = $this->request->getHeaderLine('Authorization');

        if (empty($header)) {
            return false;
        }

        if (!preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return false;
        }

        return $matches[1] === $this->token;
    }

    private function unauthorized()
    {
        return $this->respond([
            'status'  => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('per_page') ?? 10);

        $products = $this->model->paginate($perPage, 'default', $page);

        return $this->respond([
            'data' => $products,
            'pagination' => [
                'current_page' => $page,
                'per_page'     => $perPage,
                'last_page'    => $this->model->pager->getPageCount(),
                'total_data'   => $this->model->pager->getTotal(),
                'has_next'     => $page < $this->model->pager->getPageCount(),
                'has_prev'     => $page > 1,
            ]
        ]);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        // 1. Cek Autentikasi Token Bearer terlebih dahulu
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        // 2. Cari data produk berdasarkan ID
        $data = $this->model->find($id);

        // 3. Jika data tidak ditemukan, kirim respon error 404 JSON
        if (!$data) {
            return $this->failNotFound('Data produk tidak ditemukan');
        }

        // 4. Jika ada, kembalikan data produk dalam bentuk JSON
        return $this->respond($data);
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $json = $this->request->getJSON();

        $dataForm = [
            'nama'   => $json->nama ?? $this->request->getPost('nama'),
            'harga'  => $json->harga ?? $this->request->getPost('harga'),
            'jumlah' => $json->jumlah ?? $this->request->getPost('jumlah'),
        ];

        $this->model->insert($dataForm);

        return $this->respondCreated([
            'message' => 'Produk berhasil ditambahkan'
        ]);
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $json = $this->request->getJSON();

        $dataForm = [];
        if (isset($json->nama))   $dataForm['nama']   = $json->nama;
        if (isset($json->harga))  $dataForm['harga']  = $json->harga;
        if (isset($json->jumlah)) $dataForm['jumlah'] = $json->jumlah;

        // Eksekusi update data yang dikirim saja ke model bawaan kamu
        if (!empty($dataForm)) {
            $this->model->update($id, $dataForm);
        }

        return $this->respond([
            'message' => 'Produk berhasil diperbarui'
        ]);
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            'message' => 'Produk berhasil dihapus'
        ]);
    }
}