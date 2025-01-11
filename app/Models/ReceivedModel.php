<?php

namespace App\Models;

use CodeIgniter\Model;

class ReceivedModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'received';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'quantity',
        'transaction_id',
        'date_received',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function index($fromDate = null, $toDate = null)
    {
        $builder = $this->db->table('received');
        if ($fromDate !== null && $toDate !== null) {
            $builder->where('created_at >=', $fromDate)
                ->where('created_at <=', $toDate);
        }
        return $builder->orderBy('created_at', 'desc')->get()->getResultArray();
    }
}
