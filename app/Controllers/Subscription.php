<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SubscriptionModel;
use CodeIgniter\API\ResponseTrait;

class SubscriptionController extends BaseController
{
    use ResponseTrait;
    // all users
    public function index()
    {
        $model = new SubscriptionModel();
        $data['subscriptions'] = $model->orderBy('id', 'DESC')->findAll();
        return $this->respond($data);
    }
    // create
    public function create()
    {
        $model = new SubscriptionModel();
        $data = [
            'user_id' => $this->request->getVar('user_id'),
            'newspaper_id' => $this->request->getVar('newspaper_id'),
            'subscription_status'  => $this->request->getVar('subscription_status'),
            'date_subscription'  => date('Y-m-d H:i:s'),
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
        $model = new SubscriptionModel();
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
        $model = new SubscriptionModel();
        $id = $this->request->getVar('id');
        $data = [
            'user_id' => $this->request->getVar('user_id'),
            'newspaper_id' => $this->request->getVar('newspaper_id'),
            'subscription_status'  => $this->request->getVar('subscription_status'),
            'date_subscription'  => date('Y-m-d H:i:s'),
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
        $model = new SubscriptionModel();
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
