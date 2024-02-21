<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\UserModel;

class Register extends BaseController
{
    use ResponseAPIHelper;

    public function create()
    {
        if (!$this->validate([
            'email'     => 'required|is_unique[users.email]',
            'password'     => 'required|min_length[6]',
            'name'           => 'required'
        ])) {
            return $this->sendError('Validasi form gagal !',\Config\Services::validation()->getErrors());
        }

        $insert = [
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'name' => $this->request->getVar('name'),
            'address' => '123 Main St, Anytown, USA',
            'no_telp' => '123-456-7890',
            'nik_media_id' => 1,
            'profile_media_id' => 2,
            'user_type_id' => 1,
        ];

        $db = new UserModel();
        $db->insert($insert);

        if($db){
            $user = $db->where('id', $db->getInsertID())->first();
            return $this->sendSuccess(['user'=>$user],'Registrasi user berhasil !');
        }

        return $this->sendError('Registrasi user gagal !');
    }
}
