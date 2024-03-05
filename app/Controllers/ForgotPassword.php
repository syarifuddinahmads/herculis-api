<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\MailSenderHelper;
use App\Helpers\ResponseAPIHelper;
use App\Models\UserModel;
use Exception;

class ForgotPassword extends BaseController
{
    use ResponseAPIHelper, MailSenderHelper;
    public function requestResetPassword()
    {
        try{
            $db = new UserModel();
            $user  = $db->where('email', $this->request->getVar('email'))->first();
            if ($user) {
                try{
                    $this->sendResetPasswordLink($user);
                }catch(Exception $ex){
                    return $this->sendError($ex->getMessage(),null);
                }
                return $this->sendSuccess(null,'Request reset password berhasil, check email untuk mengubah password anda !');
            } else {
                return $this->sendError('Request reset password gagal, User tidak ditemukan !',null);
            }
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }

    public function updateNewPassword()
    {
        try{

            $model = new UserModel();
            $email = $this->request->getVar('email');
            $user = $model->where('email', $email)->first();
    
            $data = [
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            ];
    
            $model->update($user['id'], $data);
    
            if ($model) {
                return $this->sendSuccess(null,'Update password berhasil !');
            } else {
                return $this->sendError('Update password gagal !',null);
            }
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
}
