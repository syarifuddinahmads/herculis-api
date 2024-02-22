<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use Exception;

class User extends BaseController
{
    use ResponseAPIHelper;
    public function index()
    {
       try{
        $model = new UserModel();
        $users = $model->orderBy('id', 'DESC')->findAll();
        return $this->sendSuccess($users,'',200);

       }catch(Exception $ex){
        return $this->sendError($ex->getMessage());
       }
    }

    public function create()
    {
        if (!$this->validate([
            'email'     => 'required|is_unique[users.email]',
            'password'     => 'required|min_length[6]',
            'name'           => 'required'
        ])) {
            return $this->sendError(\Config\Services::validation()->getErrors());
        }

        try{
            $data = [
                'email' => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'name' => $this->request->getVar('name'),
            ];
            $model = new UserModel();
            $model->insert($data);
            return $this->sendSuccess(null,'Data produk berhasil ditambahkan.',201);
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }

    public function show($id = null)
    {
        try{
            $model = new UserModel();
            $data = $model->where('id', $id)->first();
            if ($data) {
                return $this->sendSuccess($data);
            } else {
                return $this->sendError('Data tidak ditemukan.');
            }
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
    
    public function update($id = null)
    {
        try{
            $model = new UserModel();
            $id = $this->request->getVar('id');
            $data = [
                'email' => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'name' => $this->request->getVar('name'),
            ];
            $model->update($id, $data);
            return $this->sendSuccess(null,'Data user berhasil diupdate.',201);
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
    
    public function delete($id = null)
    {
        $model = new UserModel();
        $data = $model->where('id', $id)->delete($id);
        if ($data) {
            $model->delete($id);
            return $this->sendSuccess(null,'Data user berhasil dihapus !',200);
        } else {
            return $this->sendError('Data tidak ditemukan.');
        }
    }
}
