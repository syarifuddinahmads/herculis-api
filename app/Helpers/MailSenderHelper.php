<?php

namespace App\Helpers;

use Exception;

trait MailSenderHelper{

    public static function sendResetPasswordLink($user=null){
        try{
            $email = \Config\Services::email();
            $email->setTo($user['email']);
            $email->setSubject('Email Test - Herculis');
            $email->setMessage('A sample email using mailtrap.');
            $email->send();
    
            if($email){
                return true;
            }else{
                return false;
            }
        }catch(Exception $ex){
            return false;
        }
    }

    public static function sendPaymentInformation(){

    }
}