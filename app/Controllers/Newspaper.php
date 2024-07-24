<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ResponseAPIHelper;
use App\Models\NewspaperModel;
use App\Models\PublisherModel;
use Exception;

class Newspaper extends BaseController
{
    private $newspaperModel;
    private $publisherModel;
    use ResponseAPIHelper;

    function __construct()
    {
        $this->newspaperModel = new NewspaperModel();
        $this->publisherModel = new PublisherModel();
    }
    
    public function index()
    {
        try{

            $publisherId = $this->request->getVar('publisher_id');

            if(!empty($publisherId)){
                $data = $this->newspaperModel->where('publisher_id',$publisherId)->orderBy('id', 'DESC')->findAll();
            }else{
                $data = $this->newspaperModel->orderBy('id', 'DESC')->findAll();
                foreach($data as &$val){
                    $publisher = $this->publisherModel->show($val['publisher_id']);
                    $val['publisher'] = $publisher;
                }
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
                'description'=> $this->request->getVar('description')
            ];

            $this->newspaperModel->insert($data);

            return $this->sendSuccess(null,'Data berhasil ditambahkan.',201);
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
   
    public function show($id = null)
    {
        $data = $this->newspaperModel->where('id', $id)->first();
        if ($data) {
            return $this->sendSuccess($data);
        } else {
            return $this->sendError('Data tidak ditemukan.');
        }
    }
    
    public function update($id = null)
    {
        try{
            $data = [
                'name' => $this->request->getVar('name'),
                'publisher_id'  => $this->request->getVar('publisher_id'),
                'price'  => $this->request->getVar('price'),
                'description'=> $this->request->getVar('description')
            ];
            $this->newspaperModel->update($id, $data);

            return $this->sendSuccess(null,'Data berhasil diupdate.',200);
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
    
    public function delete($id = null)
    {
        try{
        
            $data =$this->newspaperModel->where('id', $id)->first();
            if (!empty($data)) {
                $this->newspaperModel->where('id', $id)->delete();
                return $this->sendSuccess(null,'Data berhasil dihapus !',200);
            } else {
                return $this->sendError('Data tidak ditemukan.');
            }
        }catch(Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }
}
