<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NewspaperPriceModel;
use CodeIgniter\API\ResponseTrait;

class NewspaperPrice extends BaseController
{
    use ResponseTrait;
    // all users
    public function index()
    {
        $model = new NewspaperPriceModel();
        $data['newspaper_price'] = $model->orderBy('id', 'DESC')->findAll();
        return $this->respond($data);
    }
    // create
    public function create()
    {
        $model = new NewspaperPriceModel();
        $data = [
            'user_id'  => $this->request->getVar('user_id'),
            'newspaper_id'  => $this->request->getVar('newspaper_id'),
            'price_sale'  => $this->request->getVar('price_sale'),
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
        $model = new NewspaperPriceModel();
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
        $model = new NewspaperPriceModel();
        $data = [
            'user_id'  => $this->request->getVar('user_id'),
            'newspaper_id'  => $this->request->getVar('newspaper_id'),
            'price_sale'  => $this->request->getVar('price_sale'),
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
        $model = new NewspaperPriceModel();
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
