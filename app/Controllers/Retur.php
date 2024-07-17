<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\ReturDetailModel;
use App\Models\ReturModel;
use Exception;

class Retur extends BaseController
{
    use ResponseAPIHelper;

    protected $db; // Define the property

    public function __construct()
    {
        $this->db = \Config\Database::connect(); // Load the database library manually
    }
    
    public function index()
    {
        $fromDate = $this->request->getVar('from_date');
        $toDate = $this->request->getVar('to_date');
        $returType = $this->request->getVar('retur_type');
        $paymentStatus = $this->request->getVar('payment_status');

        $returModel = new ReturModel(); 
        $returs = $returModel->index($fromDate, $toDate, $returType, $paymentStatus);

        return $this->sendSuccess($returs,'',200);
    }

    public function show($id)
    {   
        $returModel = new ReturModel(); 
        $retur = $returModel->show($id);
        $returDetails = $returModel->getReturDetails($id);

        $retur['details'] = $returDetails;
        return $this->sendSuccess($retur,'',200);
    }

    public function create()
    {
        $this->db->transStart();

        try {
            $returCode = $this->generateReturCode();

            $returData = [
                'retur_code' => $returCode,
                'user_id' => $this->request->getVar('user_id'),
                'publisher_id' => $this->request->getVar('publisher_id'),
                'total_price' => $this->request->getVar('total_price'),
                'payment_status' => "unpaid",
                'type_retur'=> $this->request->getVar('type_retur'),
            ];

            $retur = new ReturModel();
            $retur->insert($returData);
            $returId = $this->db->insertID();

            $returDetails = $this->request->getVar('retur_details');

            $returDetailData = [];

            foreach ($returDetails as $detail) {
                $returDetailData[] = [
                    'retur_id' => $returId,
                    'newspaper_id' => $detail->newspaper_id,
                    'price' => $detail->price,
                    'quantity' => $detail->quantity,
                ];
            }

            $returDetail = new ReturDetailModel();
            $returDetail->insertBatch($returDetailData);

            $this->db->transCommit();

            return $this->sendSuccess(null, 'Data berhasil ditambahkan.', 201);
        } catch (Exception $ex) {
            $this->db->transRollback();
            return $this->sendError($ex->getMessage());
        }
    }

    private function generateReturCode()
    {
        $returDate = date('ymd');
        $lastReturCode = $this->getLastReturCode();

        return 'RTR/' . $returDate . '/' . $lastReturCode;
    }

    private function getLastReturCode()
    {
        $retur = new ReturModel();
        $lastRetur = $retur->last();
        
        if($lastRetur){
            $parts = explode('/', $lastRetur->retur_code);
            $lastPart = end($parts);
            
            $trxCode = str_pad((int)$lastPart + 1, strlen($lastPart), '0', STR_PAD_LEFT);
        }else{
            $trxCode = "00001";
        }   

        return $trxCode;

    }
}
