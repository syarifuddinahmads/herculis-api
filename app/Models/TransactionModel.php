<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class TransactionModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'transaction';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'transaction_code',
        'user_id',
        'publisher_id',
        'total_price',
        'payment_status',
        'payment_id',
        'payment_type',
        'date_transaction'
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

    public function getTransactionDetails($id)
    {
        return $this->db->table('transaction_detail')
            ->where('transaction_id', $id)
            ->get()->getResultArray();
    }
    
    public function last() {
        $builder = $this->db->table('transaction');
        $builder->select('id, transaction_code');
        $builder->whereIn('id', function(BaseBuilder $builder) {
            $builder->select('MAX(id)', false)
                    ->from('transaction');
        });
        $query = $builder->get()->getRow();
        return $query;
    }

    public function index($fromDate = null, $toDate = null, $transactionType = null, $paymentStatus = null)
    {
        $builder = $this->db->table('transaction');

        if ($fromDate !== null && $toDate !== null) {
            $builder->where('date_transaction >=', $fromDate)
                    ->where('date_transaction <=', $toDate);
        }
        if ($transactionType !== null) {
            $builder->where('transaction_type', $transactionType);
        }
        if ($paymentStatus !== null) {
            $builder->where('payment_status', $paymentStatus);
        }

        return $builder->get()->getResultArray();
    }

    public function show($id)
    {
        return $this->find($id);
    }
}
