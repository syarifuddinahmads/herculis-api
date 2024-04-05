<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\UserModel;
use CodeIgniter\Log\Logger;
use Exception;

class Register extends BaseController
{
    private $userModel;
    use ResponseAPIHelper;

    function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function create()
    {
        try {
            $insert = [
                'email' => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'name' => $this->request->getVar('name'),
            ];

            $db = $this->userModel->insert($insert);

            if ($db) {
                $user = $this->userModel->where('id', $this->userModel->getInsertID())->first();
                return $this->sendSuccess(['user' => $user], 'Registrasi user berhasil !');
            }

            return $this->sendError('Registrasi user gagal !');
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }
}
