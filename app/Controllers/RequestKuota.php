<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\NewspaperModel;
use App\Models\RequestKuotaDetailModel;
use App\Models\RequestKuotaModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

use function PHPUnit\Framework\isNull;

class RequestKuota extends BaseController
{
    protected $db;
    private $userModel;
    private $requestKuotaModel;
    private $requestKuotaDetailModel;
    private $newspaperModel;
    use ResponseAPIHelper;
    public function __construct()
    {
        $this->db = \Config\Database::connect(); // Load the database library manually
        $this->userModel = new UserModel();
        $this->requestKuotaModel = new RequestKuotaModel();
        $this->requestKuotaDetailModel = new RequestKuotaDetailModel();
        $this->newspaperModel = new NewspaperModel();
    }
    public function index()
    {
        $fromDate = $this->request->getVar('from_date');
        $toDate = $this->request->getVar('to_date');
        $status = $this->request->getVar('status');
        $paymentStatus = $this->request->getVar('payment_status');
        $asongan = $this->request->getVar('asongan_id');
        $requestKuotas = $this->requestKuotaModel->index($fromDate, $toDate, $paymentStatus, $status);
        foreach ($requestKuotas as &$requestKuota) {
            $requestKuota['asongan'] = !empty($requestKuota['user_id']) ? $this->userModel->show($requestKuota['user_id']) : null;
        }
        return $this->sendSuccess($requestKuotas, '', 200);
    }
    public function create()
    {
        $this->db->transStart();
        try {
            $requestKuota = [
                'user_id' => $this->request->getVar('user_id'),
                'total' => $this->request->getVar('total'),
                'note' => $this->request->getVar('note'),
                'status_payment' => $this->request->getVar('status_payment')
            ];
            $this->requestKuotaModel->insert($requestKuota);
            $requestKuotaId = $this->db->insertID();
            $detailsRequest = $this->request->getVar('details');
            $details = [];

            foreach ($detailsRequest as $detail) {
                $details[] = [
                    'requestKuota_id' => $requestKuotaId,
                    'newspaper_id' => $detail->newspaper_id,
                    'price' => $detail->price,
                    'quantity' => $detail->quantity,
                ];
            }
            $this->requestKuotaDetailModel->insertBatch($details);

            $this->db->transCommit();
            return $this->sendSuccess(null, 'Data berhasil ditambahkan.', 201);
        } catch (Exception $ex) {
            $this->db->transRollback();
            return $this->sendError($ex->getMessage());
        }
    }

    public function createDetail()
    {
        $this->db->transStart();
        try {
            $detail = [
                'requestKuota_id' => $this->request->getVar('requestKuota_id'),
                'newspaper_id' => $this->request->getVar('newspaper_id'),
                'price' => $this->request->getVar('price'),
                'quantity' => $this->request->getVar('quantity'),
            ];
            $this->requestKuotaDetailModel->insert($detail);
            $this->db->transCommit();
            return $this->sendSuccess(null, 'Data berhasil ditambahkan.', 201);
        } catch (Exception $ex) {
            $this->db->transRollback();
            return $this->sendError($ex->getMessage());
        }
    }

    public function show($id)
    {
        $data = $this->requestKuotaModel->show($id);
        $data['asongan'] = $this->userModel->show($data['user_id']);
        $details = $this->requestKuotaModel->getRequestKuotaDetails($id);
        foreach ($details as &$detail) {
            $detail['newspaper'] = $this->newspaperModel->show($detail['newspaper_id']);
        }
        $data['details'] = $details;
        return $this->sendSuccess($data, '', 200);
    }

    public function update($id = null)
    {
        try {
            $requestKuota = [
                'user_id' => $this->request->getVar('user_id'),
                'total' => $this->request->getVar('total'),
                'note' => $this->request->getVar('note'),
                'status_payment' => $this->request->getVar('status_payment')
            ];
            $this->requestKuotaModel->update($id, $requestKuota);
            return $this->sendSuccess(null, 'Data berhasil diupdate.', 200);
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }

    public function updateDetail($id = null)
    {
        $this->db->transStart();
        try {
            $detail = [
                'requestKuota_id' => $this->request->getVar('requestKuota_id'),
                'newspaper_id' => $this->request->getVar('newspaper_id'),
                'price' => $this->request->getVar('price'),
                'quantity' => $this->request->getVar('quantity'),
            ];
            if (isNull($id)) {
                $this->requestKuotaDetailModel->insert($detail);
            } else {
                $this->requestKuotaDetailModel->update($id, $detail);
            }
            $this->db->transCommit();
            return $this->sendSuccess(null, 'Data berhasil diupdate.', 201);
        } catch (Exception $ex) {
            $this->db->transRollback();
            return $this->sendError($ex->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $data = $this->requestKuotaModel->show($id);
            if (!empty($data)) {
                $this->requestKuotaModel->delete($id);
                return $this->sendSuccess(null, 'Data berhasil dihapus.', 201);
            } else {
                return $this->sendError("Data tidak ditemukan");
            }
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }
    public function deleteDetail($id)
    {
        try {
            $data = $this->requestKuotaDetailModel->find($id);
            if (!empty($data)) {
                $this->requestKuotaDetailModel->delete($id);
                return $this->sendSuccess(null, 'Data berhasil dihapus.', 201);
            } else {
                return $this->sendError("Data tidak ditemukan");
            }
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $data = $this->requestKuotaModel->show($id);
            if (!empty($data)) {
                $data['status'] = "approved";
                $this->requestKuotaModel->update($id, $data);
            }
            return $this->sendSuccess(null, 'Data berhasil di approved.', 200);
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }
}
