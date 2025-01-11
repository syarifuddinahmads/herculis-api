<?php

namespace App\Controllers;

use App\Helpers\ResponseAPIHelper;
use App\Models\ReceivedDetailModel;
use App\Models\ReceivedModel;
use App\Models\TransactionDetailModel;
use App\Models\TransactionModel;
use Exception;

use function PHPSTORM_META\type;

class Received extends BaseController
{
    use ResponseAPIHelper;
    private $transaction;
    private $received;
    private $receivedDetail;
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->received = new ReceivedModel();
        $this->receivedDetail = new ReceivedDetailModel();
        $this->transaction = new TransactionModel();
    }
    public function index()
    {
        $fromDate = $this->request->getVar('from_date');
        $toDate = $this->request->getVar('to_date');
        $receiveds = $this->received->index($fromDate, $toDate);
        foreach ($receiveds as &$received) {
            $received['transaction'] = !empty($received['transaction_id']) ? $this->transaction->show($received['transaction_id']) : null;
            $received['received_details'] = $this->receivedDetail->where('received_id', $received['id'])->get()->getResultArray();
        }
        return $this->sendSuccess($receiveds, '', 200);
    }

    public function show($id)
    {
        $received = $this->received->find($id);
        if (empty($received)) {
            return $this->sendError("Data tidak ditemukan");
        }
        $received['transaction'] = !empty($received['transaction_id']) ? $this->transaction->show($received['transaction_id']) : null;
        return $this->sendSuccess($received, '', 200);
    }
    public function create()
    {
        $this->db->transStart();
        try {
            $data = [
                'transaction_id' => $this->request->getVar('transaction_id'),
            ];
            $this->received->insert($data);
            $receivedId = $this->db->insertID();
            $receivedDetail = $this->request->getVar('received_details');
            $receivedDetailData = [];
            foreach ($receivedDetail as $detail) {
                $receivedDetailData[] = [
                    'received_id' => $receivedId,
                    'transaction_detail_id' => $detail->transaction_detail_id,
                    'quantity' => $detail->quantity,
                ];
            }
            $this->receivedDetail->insertBatch($receivedDetailData);
            $this->db->transCommit();
            return $this->sendSuccess(null, 'Data berhasil ditambahkan.', 201);
        } catch (Exception $ex) {
            $this->db->transRollback();
            return $this->sendError($ex->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $data = $this->received->where('id', $id)->first();
            if (!empty($data)) {
                $detail = $this->receivedDetail->where('received_id', $id)->get()->getResultArray();
                if (count($detail) > 0) {
                    $this->receivedDetail->where('received_id', $id)->delete();
                }
                $this->received->delete($id);
                return $this->sendSuccess(null, 'Data berhasil dihapus.', 201);
            } else {
                return $this->sendError("Data tidak ditemukan");
            }
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }
}
