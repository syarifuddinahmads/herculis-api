<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\NewspaperModel;
use App\Models\RequestKuotaDetailModel;
use App\Models\RequestKuotaModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    protected $db;
    private $userModel;
    private $transaction;
    private $requestKuotaModel;
    private $requestKuotaDetailModel;
    private $newspaperModel;
    use ResponseAPIHelper;
    public function __construct()
    {
        $this->db = \Config\Database::connect(); // Load the database library manually
        $this->userModel = new UserModel();
        $this->transaction = new TransactionModel();
        $this->requestKuotaModel = new RequestKuotaModel();
        $this->requestKuotaDetailModel = new RequestKuotaDetailModel();
        $this->newspaperModel = new NewspaperModel();
    }
    public function hutangAsongan($id = null)
    {
        $data = $this->requestKuotaModel->getHutang($id);
        $result = [];
        foreach ($data as $value) {
            $result[] = [
                "asongan" => !empty($value['user_id']) ? $this->userModel->show($value['user_id']) : null,
                "total" => $value['total']
            ];
        }
        return $this->sendSuccess($result, 'Data berhasil ditemukan.', 201);
    }
    public function hutangAdmin($id = null)
    {
        $data = $this->transaction->getHutang($id);
        $result = [];
        foreach ($data as $value) {
            $result[] = [
                "admin" => !empty($value['user_id']) ? $this->userModel->show($value['user_id']) : null,
                "total" => $value['total_price']
            ];
        }
        return $this->sendSuccess($result, 'Data berhasil ditemukan.', 201);
    }
    public function asongan()
    {
        $todaydate = date('Y-m-d');
        $count = $this->requestKuotaModel->getCountRequest($todaydate);
        // $pernjualanTerbaru = $this->requestKuotaModel->
    }
}
