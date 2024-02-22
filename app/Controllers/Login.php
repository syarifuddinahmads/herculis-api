<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Libraries\JWTLibrary;
use App\Models\UserModel;

class Login extends BaseController
{
    use ResponseAPIHelper;
    public function login()
    {
        if (!$this->validate([
            'email'     => 'required',
            'password'     => 'required|min_length[6]',
        ])) {
            return $this->sendError('Validasi form gagal !',\Config\Services::validation()->getErrors());
        }

        $db = new UserModel;
        $user  = $db->where('email', $this->request->getVar('email'))->first();
        if ($user) {
            if (password_verify($this->request->getVar('password'), $user['password'])) {
                $jwt = new JWTLibrary;
                $token = $jwt->token();

                $data = [
                    'token' => $token,
                    'user'=>$user
                ];

                return $this->sendSuccess($data,'Login Berhasil !',200);
            }else{
                return $this->sendError('Login Gagal, Email atau password salah !',null);
            }
        } else {
            return $this->sendError('Login Gagal, User tidak ditemukan !',null);
        }
    }
}
