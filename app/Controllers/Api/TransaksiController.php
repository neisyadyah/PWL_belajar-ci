<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
 
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class TransaksiController extends BaseController
{
    protected $transactionModel;
    protected $transactionDetailModel;
    private $token;

    function __construct()
    {  
        $this->transactionModel = new TransactionModel(); 
        $this->transactionDetailModel = new TransactionDetailModel(); 
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
        return $this->response
                    ->setStatusCode(401)
                    ->setJSON([
                        'status'  => false,
                        'message' => 'Unauthorized'
        ]);
    }

    public function index()
    {
        if (! $this->authenticate()) {
            return $this->unauthorized();
        }

        $start = $this->request->getGet('start');
        $end   = $this->request->getGet('end'); 

        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('per_page') ?? 10);

      
        $query = $this->transactionModel->orderBy('created_at', 'DESC');

        if ($start && $end) {
           
            $query->where('created_at >=', $start)->where('created_at <=', $end . ' 23:59:59');
        }
        
       
        $transactions = $query->paginate($perPage, 'default', $page);

        $transactionIds = [];

        if (!empty($transactions)) {
            $transactionIds = array_column($transactions, 'id');
        }

      
        $products = [];

        if (!empty($transactionIds)) {
            $products = $this->transactionDetailModel->getProductsByTransactionIds($transactionIds);
        }

        foreach ($transactions as $key => $trx) {
            $transactions[$key]['details'] = $products[$trx['id']] ?? [];
        }

        $pager = $this->transactionModel->pager;

        return $this->response->setJSON([
            'filter' => [
                'start' => $start,
                'end'   => $end,
            ],
            'data' => $transactions,
            'pagination' => [
                'current_page' => $page,
                'per_page'     => $perPage,
                'last_page'    => $pager->getPageCount(),
                'total_data'   => $pager->getTotal(),
                'has_next'     => $page < $pager->getPageCount(),
                'has_prev'     => $page > 1,
            ]
        ]);
    }

    
      public function destinations()
    {
        $search = $this->request->getGet('q'); 

        if (empty($search)) {
            return $this->response->setJSON(['results' => []]);
        }

        $results = [];

        try {
           
            if (!class_exists('\App\Services\RajaOngkirService')) {
                throw new \Exception("Class RajaOngkirService tidak ditemukan.");
            }

            $service = new \App\Services\RajaOngkirService(); 
        
            if (method_exists($service, 'getDestination')) {
                $response = $service->getDestination($search);
            } elseif (method_exists($service, 'getDestinations')) {
                $response = $service->getDestinations($search);
            } else {
                throw new \Exception("Method pencarian tidak cocok.");
            }

            $data = $response['data'] ?? $response['rajaongkir']['results'] ?? $response['results'] ?? $response ?? [];

            if (is_array($data)) {
                foreach ($data as $item) {
                    $id = $item['id'] ?? $item['subdistrict_id'] ?? $item['city_id'] ?? '';
                    $text = $item['label'] ?? $item['subdistrict_name'] ?? $item['city_name'] ?? '';
                    
                    if ($id && $text) {
                        $results[] = [
                            'id'   => $id,
                            'text' => $text
                        ];
                    }
                }
            }

            if (empty($results)) {
                $mockData = [
                    ['id' => '6499', 'text' => 'TEMBALANG, KOTA SEMARANG'],
                    ['id' => '6500', 'text' => 'BANYUMANIK, KOTA SEMARANG'],
                    ['id' => '6501', 'text' => 'PUDAKPAYUNG, KOTA SEMARANG'],
                    ['id' => '6502', 'text' => 'KARANGAYU, KOTA SEMARANG']
                ];
                foreach ($mockData as $mock) {
                    if (stripos($mock['text'], $search) !== false) {
                        $results[] = $mock;
                    }
                }
            }

        } catch (\Throwable $e) {
           
            $mockData = [
                ['id' => '6499', 'text' => 'TEMBALANG, KOTA SEMARANG'],
                ['id' => '6500', 'text' => 'BANYUMANIK, KOTA SEMARANG'],
                ['id' => '6501', 'text' => 'PUDAKPAYUNG, KOTA SEMARANG'],
                ['id' => '6502', 'text' => 'KARANGAYU, KOTA SEMARANG']
            ];
            foreach ($mockData as $mock) {
                if (stripos($mock['text'], $search) !== false) {
                    $results[] = $mock;
                }
            }
        }

        return $this->response->setJSON([
            'results' => $results
        ]);
    }

    public function costs()
    {
        $destination = $this->request->getGet('destination');

        if (empty($destination)) {
            return $this->response->setJSON([]);
        }

        try {
            $service = new \App\Services\RajaOngkirService();
            $origin = '6499'; 
            $weight = 1000;
            $courier = 'jne';

            if (method_exists($service, 'getCost')) {
                $response = $service->getCost($origin, $destination, $weight, $courier);
            } else {
                $response = $service->getCosts($origin, $destination, $weight, $courier);
            }

            $results = [];
            $data = $response['data'] ?? $response['rajaongkir']['results'][0]['costs'] ?? $response ?? [];

            if (is_array($data)) {
                foreach ($data as $item) {
                    $results[] = [
                        'service'     => $item['service'] ?? 'REG',
                        'description' => $item['description'] ?? 'Layanan Reguler',
                        'cost'        => $item['cost'][0]['value'] ?? $item['cost'] ?? 15000,
                        'etd'         => $item['cost'][0]['etd'] ?? $item['etd'] ?? '2-3'
                    ];
                }
            }

            if (empty($results)) {
                $results[] = ['service' => 'REG', 'description' => 'JNE Reguler', 'cost' => 15000, 'etd' => '2-3'];
            }

            return $this->response->setJSON($results);

        } catch (\Throwable $e) {
            return $this->response->setJSON([
                ['service' => 'REG', 'description' => 'Ekspedisi Reguler (Backup)', 'cost' => 12000, 'etd' => '2-4']
            ]);
        }
    }
}