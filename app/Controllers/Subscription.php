<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\NewspaperModel;
use App\Models\SubscriptionModel;
use App\Models\UserModel;
use Exception;

class Subscription extends BaseController
{
    use ResponseAPIHelper;

    private $subscriptionModel;
    private $userModel;
    private $newspaperModel;

    public function __construct()
    {
        $this->subscriptionModel = new SubscriptionModel();
        $this->userModel = new UserModel();
        $this->newspaperModel = new NewspaperModel();
    }

    public function index()
    {
        try {
            $subscriptions = $this->subscriptionModel->orderBy('id', 'DESC')->findAll();
            foreach ($subscriptions as &$subs) {
                $subs['newspaper'] = $this->newspaperModel->show($subs['newspaper_id']);
                $subs['user'] = $this->userModel->show($subs['user_id']);
            }

            return $this->sendSuccess($subscriptions);
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }

    public function create()
    {
        try {
            $data = [
                'user_id' => $this->request->getVar('user_id'),
                'newspaper_id' => $this->request->getVar('newspaper_id'),
                'subscription_status'  => $this->request->getVar('subscription_status'),
                'date_subscription'  => date('Y-m-d H:i:s'),
            ];
            $this->subscriptionModel->insert($data);
            return $this->sendSuccess('Data berhasil ditambahkan.');
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), null, 500);
        }
    }
    // single user
    public function show($id = null)
    {
        try {
            $data = $this->subscriptionModel->where('id', $id)->first();

            if ($data) {
                return $this->sendSuccess($data);
            } else {
                return $this->sendError('Data tidak ditemukan.');
            }
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }
    // update
    public function update($id = null)
    {
        try {
            $data = [
                'user_id' => $this->request->getVar('user_id'),
                'newspaper_id' => $this->request->getVar('newspaper_id'),
                'subscription_status'  => $this->request->getVar('subscription_status'),
            ];
            $this->subscriptionModel->update($id, $data);

            return $this->sendSuccess(null, 'Data berhasil diupdate.', 200);
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }
    // delete
    public function delete($id = null)
    {
        try {
            $data = $this->subscriptionModel->where('id', $id)->delete($id);
            if ($data) {
                $this->subscriptionModel->delete($id);
                return $this->sendSuccess('Data berhasil dihapus.');
            } else {
                return $this->sendError('Data tidak ditemukan.');
            }
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }
}
