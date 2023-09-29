<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class RegisterController extends BaseController
{
    use ResponseTrait;

    public function create()
    {
        if (!$this->validate([
            'email'     => 'required|is_unique[users.email]',
            'password'     => 'required|min_length[6]',
            'name'           => 'required'
        ])) {
            return $this->response->setJSON(['success' => false, 'data' => null, "message" => \Config\Services::validation()->getErrors()]);
        }

        $insert = [
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'name' => $this->request->getVar('name'),
        ];

        $db = new UserModel();
        $db->insert($insert);

        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Registrasi berhasil ditambahkan.'
            ]
        ];
        return $this->respondCreated($response);
    }
}
