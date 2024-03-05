<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\UserTypeModel;
use Exception;

class UserType extends BaseController
{
    use ResponseAPIHelper;
    public function index()
    {
       try{
        $model = new UserTypeModel();
        $data = $model->orderBy('id', 'DESC')->findAll();
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
            $model = new UserTypeModel();
            $model->insert($data);
            return $this->sendSuccess(null,'Data berhasil ditambahkan.',201);
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }

    public function show($id = null)
    {
        try{
            $model = new UserTypeModel();
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
            $model = new UserTypeModel();
            $data = [
                'name' => $this->request->getVar('name'),
            ];
            $model->update($id, $data);
            return $this->sendSuccess(null,'Data berhasil diupdate.',200);
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
    
    public function delete($id = null)
    {
        try{
            $model = new UserTypeModel();
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
