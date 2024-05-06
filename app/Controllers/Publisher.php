<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\PublisherModel;
use CodeIgniter\API\ResponseTrait;
use Exception;

class Publisher extends BaseController
{
    private $publisherModel;
    use ResponseAPIHelper;

    function __construct()
    {
        $this->publisherModel = new PublisherModel();
    }

    public function index()
    {
        try {
            $data = $this->publisherModel->orderBy('id', 'DESC')->findAll();
            return $this->sendSuccess($data, '', 200);
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }

    public function create()
    {
        try {
            $data = [
                'name' => $this->request->getVar('name'),
                'address'  => $this->request->getVar('address'),
                'no_telp'  => $this->request->getVar('no_telp'),
            ];

            $this->publisherModel->insert($data);

            return $this->sendSuccess(null, 'Data berhasil ditambahkan.', 201);
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }

    public function show($id = null)
    {
        $data = $this->publisherModel->where('id', $id)->first();
        if ($data) {
            return $this->sendSuccess($data);
        } else {
            return $this->sendError('Data tidak ditemukan.');
        }
    }

    public function update($id = null)
    {
        try {

            $data = [
                'name' => $this->request->getVar('name'),
                'address'  => $this->request->getVar('address'),
                'no_telp'  => $this->request->getVar('no_telp'),
            ];
            $this->publisherModel->update($id, $data);

            return $this->sendSuccess(null, 'Data berhasil diupdate.', 200);
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }

    public function delete($id = null)
    {
        try {
            $data = $this->publisherModel->where('id', $id)->first();
            if (!empty($data)) {
                $this->publisherModel->where('id', $id)->delete();
                return $this->sendSuccess(null, 'Data berhasil dihapus !', 200);
            } else {
                return $this->sendError('Data tidak ditemukan.');
            }
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }
}
