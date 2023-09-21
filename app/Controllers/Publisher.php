<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PublisherModel;
use CodeIgniter\API\ResponseTrait;

class Publisher extends BaseController
{
    use ResponseTrait;
    // all users
    public function index()
    {
        $model = new PublisherModel();
        $data['publishers'] = $model->orderBy('id', 'DESC')->findAll();
        return $this->respond($data);
    }
    // create
    public function create()
    {
        $model = new PublisherModel();
        $data = [
            'name' => $this->request->getVar('name'),
            'address'  => $this->request->getVar('address'),
            'no_telp'  => $this->request->getVar('no_telp'),
        ];
        $model->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Data berhasil ditambahkan.'
            ]
        ];
        return $this->respondCreated($response);
    }
    // single user
    public function show($id = null)
    {
        $model = new PublisherModel();
        $data = $model->where('id', $id)->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('Data tidak ditemukan.');
        }
    }
    // update
    public function update($id = null)
    {
        $model = new PublisherModel();
        $data = [
            'name' => $this->request->getVar('name'),
            'address'  => $this->request->getVar('address'),
            'no_telp'  => $this->request->getVar('no_telp'),
        ];
        $model->update($id, $data);
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Data berhasil diubah.'
            ]
        ];
        return $this->respond($response);
    }
    // delete
    public function delete($id = null)
    {
        $model = new PublisherModel();
        $data = $model->where('id', $id)->delete($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data berhasil dihapus.'
                ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('Data tidak ditemukan.');
        }
    }
}
