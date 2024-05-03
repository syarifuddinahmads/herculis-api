<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use Exception;

class Transaction extends BaseController
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
        $transactionType = $this->request->getVar('transaction_type');
        $paymentStatus = $this->request->getVar('payment_status');

        $transactionModel = new TransactionModel(); 
        $transactions = $transactionModel->index($fromDate, $toDate, $transactionType, $paymentStatus);

        return $this->sendSuccess($transactions,'',200);
    }

    public function show($id)
    {   
        $transactionModel = new TransactionModel(); 
        $transaction = $transactionModel->show($id);
        $transactionDetails = $transactionModel->getTransactionDetails($id);

        $transaction['details'] = $transactionDetails;
        return $this->sendSuccess($transaction,'',200);
    }

    public function create()
    {
        $this->db->transStart();

        try {
            $transactionCode = $this->generateTransactionCode();

            $transactionData = [
                'transaction_code' => $transactionCode,
                'user_id' => $this->request->getVar('user_id'),
                'publisher_id' => $this->request->getVar('publisher_id'),
                'total_price' => $this->request->getVar('total_price'),
                'payment_status' => "unpaid",
                'payment_id' => $this->request->getVar('payment_id'),
                'payment_type' => $this->request->getVar('payment_type'),
                'date_transaction' => $this->request->getVar('date_transaction'),
            ];

            $transaction = new TransactionModel();
            $transaction->insert($transactionData);
            $transactionId = $this->db->insertID();

            $transactionDetails = $this->request->getVar('transaction_details');

            $transactionDetailData = [];

            foreach ($transactionDetails as $detail) {
                $transactionDetailData[] = [
                    'transaction_id' => $transactionId,
                    'newspaper_id' => $detail->newspaper_id,
                    'price' => $detail->price,
                    'quantity' => $detail->quantity,
                ];
            }

            $transactionDetail = new TransactionDetailModel();
            $transactionDetail->insertBatch($transactionDetailData);

            $this->db->transCommit();

            return $this->sendSuccess(null, 'Data berhasil ditambahkan.', 201);
        } catch (Exception $ex) {
            $this->db->transRollback();
            return $this->sendError($ex->getMessage());
        }
    }

    private function generateTransactionCode()
    {
        $transactionDate = date('ymd');
        $lastTransactionCode = $this->getLastTransactionCode();

        return 'inv/' . $transactionDate . '/' . $lastTransactionCode;
    }

    private function getLastTransactionCode()
    {
        $transaction = new TransactionModel();
        $lastTransaction = $transaction->last();
        
        if($lastTransaction){
            $parts = explode('/', $lastTransaction->transaction_code);
            $lastPart = end($parts);
            
            $trxCode = str_pad((int)$lastPart + 1, strlen($lastPart), '0', STR_PAD_LEFT);
        }else{
            $trxCode = "00001";
        }   

        return $trxCode;

    }
}
