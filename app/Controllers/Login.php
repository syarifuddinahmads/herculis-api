<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Libraries\JWTLibrary;
use App\Models\UserModel;
use Exception;

class Login extends BaseController
{
    use ResponseAPIHelper;
    public function login()
    {
        try{
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
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
}
