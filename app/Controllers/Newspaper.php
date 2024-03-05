<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\NewspaperModel;
use Exception;

class Newspaper extends BaseController
{
    use ResponseAPIHelper;
    
    public function index()
    {
        try{

            $publisherId = $this->request->getVar('publisher_id');

            $model = new NewspaperModel();

            if(!empty($publisherId)){
                $data = $model->where('publisher_id',$publisherId)->orderBy('id', 'DESC')->findAll();
            }else{
                $data = $model->orderBy('id', 'DESC')->findAll();
            }
           
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
                'publisher_id'  => $this->request->getVar('publisher_id'),
                'price'  => $this->request->getVar('price'),
            ];

            $model = new NewspaperModel();
            $model->insert($data);

            return $this->sendSuccess(null,'Data berhasil ditambahkan.',201);
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
   
    public function show($id = null)
    {
        $model = new NewspaperModel();
        $data = $model->where('id', $id)->first();
        if ($data) {
            return $this->sendSuccess($data);
        } else {
            return $this->sendError('Data tidak ditemukan.');
        }
    }
    
    public function update($id = null)
    {
        try{

            $model = new NewspaperModel();
            $data = [
                'name' => $this->request->getVar('name'),
                'publisher_id'  => $this->request->getVar('publisher_id'),
                'price'  => $this->request->getVar('price'),
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
            
            $model = new NewspaperModel();
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
