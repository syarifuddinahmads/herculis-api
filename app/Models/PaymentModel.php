<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'payment';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'type_payment',
        'transaction_id',
        'date_payment',
        'status_payment',
        'note',
        'image'
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
        $builder = $this->db->table('payment');
        if ($fromDate !== null && $toDate !== null) {
            $builder->where('date_payment >=', $fromDate)
                ->where('date_payment <=', $toDate);
        }
        return $builder->orderBy('date_payment', 'desc')->get()->getResultArray();
    }
}
