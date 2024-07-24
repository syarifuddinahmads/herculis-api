<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\NewspaperModel;
use App\Models\PublisherModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\UserModel;
use CodeIgniter\Log\Logger;
use Exception;

class Transaction extends BaseController
{   
    use ResponseAPIHelper;

    private $publisherModel;
    private $userModel;
    private $newspaperModel;
    protected $db; // Define the property

    public function __construct()
    {
        $this->db = \Config\Database::connect(); // Load the database library manually
        $this->userModel= new UserModel();
        $this->publisherModel = new PublisherModel();
        $this->newspaperModel = new NewspaperModel();
    }
    
    public function index()
    {
        $fromDate = $this->request->getVar('from_date');
        $toDate = $this->request->getVar('to_date');
        $transactionType = $this->request->getVar('transaction_type');
        $paymentStatus = $this->request->getVar('payment_status');

        $transactionModel = new TransactionModel(); 
        $transactions = $transactionModel->index($fromDate, $toDate, $transactionType, $paymentStatus);

        foreach($transactions as &$transaction){
            $transaction['publisher'] = !empty($transaction['publisher_id']) ? $this->publisherModel->show($transaction['publisher_id']):null;
            $transaction['staff'] = $this->userModel->show($transaction['staff_id']);
            $transaction['asongan'] = !empty($transaction['user_id'])?$this->userModel->show($transaction['user_id']):null;
        }

        return $this->sendSuccess($transactions,'',200);
    }

    public function show($id)
    {   
        $transactionModel = new TransactionModel(); 
        $transaction = $transactionModel->show($id);
        $transaction['publisher'] = !empty($transaction['publisher_id']) ? $this->publisherModel->show($transaction['publisher_id']):null;
        $transaction['staff'] = $this->userModel->show($transaction['staff_id']);
        $transaction['asongan'] = !empty($transaction['user_id'])?$this->userModel->show($transaction['user_id']):null;
        $transactionDetails = $transactionModel->getTransactionDetails($id);

        foreach($transactionDetails as &$transactionDetail){
            $transactionDetail['newspaper'] = $this->newspaperModel->show($transactionDetail['newspaper_id']);
        }

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
                'staff_id' => $this->request->getVar('staff_id'),
                'publisher_id' => $this->request->getVar('publisher_id'),
                'total_price' => $this->request->getVar('total_price'),
                'payment_status' => "unpaid",
                'type_transaction'=> $this->request->getVar('type_transaction'),
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

        return 'INV/' . $transactionDate . '/' . $lastTransactionCode;
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
