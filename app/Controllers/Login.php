<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Libraries\JWTLibrary;
use App\Models\UserModel;
use App\Models\UserTypeModel;
use Exception;

class Login extends BaseController
{
    use ResponseAPIHelper;
    private $userModel;
    private $userTypeModel;

    function __construct()
    {
        $this->userModel = new UserModel();
        $this->userTypeModel = new UserTypeModel();
    }

    public function login()
    {
        try {
            $user  = $this->userModel->where('email', $this->request->getVar('email'))->first();
            if ($user) {
                if (password_verify($this->request->getVar('password'), $user['password'])) {
                    $jwt = new JWTLibrary;
                    $token = $jwt->token();

                   
                    $userType = $this->userTypeModel->show($user['user_type_id']);
                    $user['user_type'] = $userType;

                    $data = [
                        'token' => $token,
                        'user' => $user,
                        'user_type'=>$userType
                    ];

                    return $this->sendSuccess($data, 'Login Berhasil !', 200);
                } else {
                    return $this->sendError('Login Gagal, Email atau password salah !', null,403);
                }
            } else {
                return $this->sendError('Login Gagal, User tidak ditemukan !', null,403);
            }
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }
}
