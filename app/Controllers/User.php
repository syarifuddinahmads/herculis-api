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
        try{
            $data = [
                'email' => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'name' => $this->request->getVar('name'),
            ];
            $model = new UserModel();
            $model->insert($data);
            return $this->sendSuccess(null,'Data berhasil ditambahkan.',201);
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
            $data = [
                'email' => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'name' => $this->request->getVar('name'),
                'no_telp' => $this->request->getVar('no_telp'),
            ];
            $model->update($id, $data);
            return $this->sendSuccess(null,'Data berhasil diupdate.',201);
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
    
    public function delete($id = null)
    {
        try{
            $model = new UserModel();
            $data = $model->where('id', $id)->first();
            if (!empty($data)) {
                $model->where('id', $id)->delete();
                return $this->sendSuccess(null,'Data berhasil dihapus !',200);
            } else {
                return $this->sendError('Data tidak ditemukan.');
            }
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
}
