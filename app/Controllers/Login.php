<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\JWTLibrary;
use App\Models\UserModel;

class Login extends BaseController
{
    public function login()
    {
        if (!$this->validate([
            'email'     => 'required',
            'password'     => 'required|min_length[6]',
        ])) {
            return $this->response->setJSON(['success' => false, 'data' => null, "message" => \Config\Services::validation()->getErrors()]);
        }

        $db = new UserModel;
        $user  = $db->where('email', $this->request->getVar('email'))->first();
        if ($user) {
            if (password_verify($this->request->getVar('password'), $user['password'])) {
                $jwt = new JWTLibrary;
                $token = $jwt->token();

                return $this->response->setJSON(['token' => $token]);
            }
        } else {

            return $this->response->setJSON(['success' => false, 'message' => 'User not found'])->setStatusCode(409);
        }
    }
}
