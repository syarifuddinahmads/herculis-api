<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\PaymentModel;
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

            $this->validation = \Config\Services::validation();

            $this->validation->setRules([
                'payment_type' => 'required|in_list[pembelian,penjualan]',
                'transaction_details' => 'required',
            ]);

            if (!$this->validation->withRequest($this->request)->run()) {
                return $this->sendError(implode(" ",$this->validation->getErrors()));
            }

            $transactionCode = $this->generateTransactionCode();

            $transactionData = [
                'transaction_code' => $transactionCode,
                'user_id' => $this->request->getVar('user_id'),
                'publisher_id' => $this->request->getVar('publisher_id'),
                'total_price' => $this->request->getVar('total_price'),
                'payment_status' => "unpaid",
                'payment_type' => $this->request->getVar('payment_type'),
                'date_transaction' => date('Y-m-d H:i:s'),
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

    public function payment()
    {   
        $this->validation = \Config\Services::validation();

        $this->validation->setRules([
            'type_payment' => 'required|in_list[pembelian,penjualan]',
            'transaction_id' => 'required|integer',
            'note' => 'required',
            'file' => 'uploaded[file]|is_image[file]'
        ]);
        
        if (!$this->validation->withRequest($this->request)->run()) {
            return $this->sendError(implode(" ",$this->validation->getErrors()));
        }

        $transactionId = $this->request->getVar('transaction_id');
        $transactionModel = new TransactionModel(); 
        $existingTransaction = $transactionModel
            ->where('payment_id IS NOT NULL')
            ->where('id', $transactionId)
            ->get()->getRow();

        if ($existingTransaction) {
            return $this->sendError('payment already exists in transaction');
        }

        $file = $this->request->getFile('file');

        if ($file && $file->isValid() && $file->getClientMimeType() === 'image/jpeg' || $file->getClientMimeType() === 'image/png' || $file->getClientMimeType() === 'image/gif') {
            $newName = $file->getRandomName();
            $file->move('uploads', $newName); 
            $fileUrl = base_url('uploads/' . $newName); 
        } else {
            return $this->sendError('Invalid file. Only JPEG, PNG, or GIF images are allowed.');
        }

        $paymentData = [
            'type_payment' => $this->request->getVar('type_payment'),
            'date_payment' => date('Y-m-d H:i:s'),
            'status_payment' => 'paid',
            'file_url' => $fileUrl,
            'note' => $this->request->getVar('note')
        ];

        $payment = new PaymentModel();
        $payment->insert($paymentData);
        $paymentId = $this->db->insertID();


        $transactionModel
            ->where('id', $transactionId)
            ->update($transactionId,['payment_id' => $paymentId]);
        
        return $this->sendSuccess(null, 'Data berhasil ditambahkan.', 201);
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
