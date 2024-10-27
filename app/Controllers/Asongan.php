<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\UserModel;
use App\Models\UserTypeModel;
use CodeIgniter\API\ResponseTrait;
use Exception;

class Asongan extends BaseController
{
    private $userModel;
    private $userTypeModel;
    use ResponseAPIHelper;

    function __construct()
    {
        $this->userModel = new UserModel();
        $this->userTypeModel = new UserTypeModel();
    }
    public function index()
    {
       try{
            $users = $this->userModel->orderBy('id', 'DESC')->findAll();
            foreach ($users as &$user) {
                $userType = $this->userTypeModel->show($user['user_type_id']);
                $user['user_type'] = $userType;
            }
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
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'name' => $this->request->getVar('name'),
                'user_type_id'=>$this->request->getVar('user_type_id'),
                'no_telp'=>$this->request->getVar('no_telp'),
                'address'=>$this->request->getVar('address'),
            ];
            $this->userModel->insert($data);
            return $this->sendSuccess(null,'Data berhasil ditambahkan.',201);
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage(),null,500);
        }
    }

    public function show($id = null)
    {
        try{
            $data = $this->userModel->where('id', $id)->first();
            $userType = $this->userTypeModel->where('id', $data['user_type_id'])->first();

            $data['user_type'] = $userType;

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
            $data = [
                'email' => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'name' => $this->request->getVar('name'),
                'user_type_id'=>$this->request->getVar('user_type_id'),
                'no_telp'=>$this->request->getVar('no_telp'),
                'address'=>$this->request->getVar('address'),
                'nik_image'=>$this->request->getVar('nik_image'),
                'profile_image'=>$this->request->getVar('profile_image'),
            ];
            $this->userModel->update($id, $data);
            return $this->sendSuccess(null,'Data berhasil diupdate.',201);
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
    
    public function delete($id = null)
    {
        try{
            $data = $this->userModel->where('id', $id)->first();
            if (!empty($data)) {
                $this->userModel->where('id', $id)->delete();
                return $this->sendSuccess(null,'Data berhasil dihapus !',200);
            } else {
                return $this->sendError('Data tidak ditemukan.');
            }
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
}
