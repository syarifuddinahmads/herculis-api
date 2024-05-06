<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\UserModel;
use App\Models\UserTypeModel;
use Exception;

class UserType extends BaseController
{   private $userTypeModel;
    private $userModel;
    use ResponseAPIHelper;

    function __construct()
    {
        $this->userTypeModel = new UserTypeModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
       try{
        $data = $this->userTypeModel->orderBy('id', 'DESC')->findAll();
        return $this->sendSuccess($data,'',200);

       }catch(Exception $ex){
        return $this->sendError($ex->getMessage());
       }
    }

    public function create()
    {
        try{
            $data = [
                'name' => $this->request->getVar('name'),
            ];
            $this->userTypeModel->insert($data);
            return $this->sendSuccess(null,'Data berhasil ditambahkan.',201);
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }

    public function show($id = null)
    {
        try{
            $data = $this->userTypeModel->where('id', $id)->first();
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
                'name' => $this->request->getVar('name'),
            ];
            $this->userTypeModel->update($id, $data);
            return $this->sendSuccess(null,'Data berhasil diupdate.',200);
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
    
    public function delete($id = null)
    {
        try{
            $data = $this->userTypeModel->where('id', $id)->first();
            if (!empty($data)) {
                $this->userTypeModel->where('id', $id)->delete();
                return $this->sendSuccess(null,'Data berhasil dihapus !',200);
            } else {
                return $this->sendError('Data tidak ditemukan.');
            }
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
}
