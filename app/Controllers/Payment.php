<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\PaymentModel;
use App\Models\TransactionModel;
use Exception;

class Payment extends BaseController
{
    use ResponseAPIHelper;
    private $transaction;
    private $payment;
    protected $db; // Define the property

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->payment = new PaymentModel();
        $this->transaction = new TransactionModel();
    }
    public function index()
    {
        $fromDate = $this->request->getVar('from_date');
        $toDate = $this->request->getVar('to_date');
        $payments = $this->payment->index($fromDate, $toDate);
        foreach ($payments as &$payment) {
            $payment['transaction'] = !empty($payment['transaction_id']) ? $this->transaction->show($payment['transaction_id']) : null;
        }
        return $this->sendSuccess($payments, '', 200);
    }

    public function show($id)
    {
        $payment = $this->payment->find($id);
        if (empty($payment)) {
            return $this->sendError("Data tidak ditemukan");
        }
        $payment['transaction'] = !empty($transaction['transaction_id']) ? $this->transaction->show($payment['transaction_id']) : null;
        return $this->sendSuccess($payment, '', 200);
    }
    public function create()
    {
        $this->db->transStart();
        try {
            $transaction = $this->transaction->where('id', $this->request->getVar('transaction_id'))->first();
            if (empty($transaction)) {
                $this->db->transRollback();
                return $this->sendError("Data Transaksi tidak ditemukan");
            }
            $data = [
                'type_payment' => $this->request->getVar('type_payment'),
                'transaction_id'  => $this->request->getVar('transaction_id'),
                'status_payment'  => $this->request->getVar('status_payment'),
                'date_payment' => $this->request->getVar('date_payment')
            ];
            $transaction['payment_status'] = "paid";
            $this->payment->insert($data);
            $this->transaction->update($transaction['id'], $transaction);
            $this->db->transCommit();
            return $this->sendSuccess(null, 'Data berhasil ditambahkan.', 201);
        } catch (Exception $ex) {
            $this->db->transRollback();
            return $this->sendError($ex->getMessage());
        }
    }
    public function update($id = null)
    {
        try {
            $data = [
                'type_payment' => $this->request->getVar('type_payment'),
                'transaction_id'  => $this->request->getVar('transaction_id'),
                'status_payment'  => $this->request->getVar('status_payment'),
                'date_payment' => $this->request->getVar('date_payment')
            ];
            $this->payment->update($id, $data);
            return $this->sendSuccess(null, 'Data berhasil diupdate.', 200);
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            $data = $this->payment->where("id", $id)->first();
            if (!empty($data)) {
                $transaction = $this->transaction->where('id', $data['transaction_id'])->first();
                if (!empty($transaction)) {
                    $transaction['payment_status'] = "unpaid";
                    $this->transaction->update($transaction['id'], $transaction);
                }
                $this->payment->delete($id);
                return $this->sendSuccess(null, 'Data berhasil dihapus.', 201);
            } else {
                return $this->sendError("Data tidak ditemukan");
            }
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }
}
