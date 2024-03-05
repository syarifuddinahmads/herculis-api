<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\NewspaperPriceModel;
use CodeIgniter\API\ResponseTrait;
use Exception;

class NewspaperPrice extends BaseController
{
    use ResponseAPIHelper;
    
    public function index()
    {
        try{
            $userId = $this->request->getVar('user_id');
            $model = new NewspaperPriceModel();
            if(!empty($userId)){
                $data = $model->where('user_id',$userId)->orderBy('id', 'DESC')->findAll();
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
                'user_id' => $this->request->getVar('user_id'),
                'newspaper_id'  => $this->request->getVar('newspaper_id'),
                'price_sale'  => $this->request->getVar('price_sale'),
                'price'=> $this->request->getVar('price'),
            ];

            $model = new NewspaperPriceModel();
            $model->insert($data);

            return $this->sendSuccess(null,'Data berhasil ditambahkan.',201);
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
   
    public function show($id = null)
    {
        $model = new NewspaperPriceModel();
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

            $model = new NewspaperPriceModel();
            $data = [
                'user_id' => $this->request->getVar('user_id'),
                'newspaper_id'  => $this->request->getVar('newspaper_id'),
                'price_sale'  => $this->request->getVar('price_sale'),
                'price'=> $this->request->getVar('price'),
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
            $model = new NewspaperPriceModel();
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
