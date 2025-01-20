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
        'staff_id',
        'publisher_id',
        'total_price',
        'payment_status',
        'date_transaction',
        'type_transaction',
        'note'
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

    public function last()
    {
        $builder = $this->db->table('transaction');
        $builder->select('id, transaction_code');
        $builder->whereIn('id', function (BaseBuilder $builder) {
            $builder->select('MAX(id)', false)
                ->from('transaction');
        });
        $query = $builder->get()->getRow();
        return $query;
    }

    public function index($search = null, $fromDate = null, $toDate = null, $transactionType = null, $paymentStatus = null)
    {
        $builder = $this->db->table('transaction');

        if ($search !== null) {
            $builder->like('transaction_code', $search);
        }

        if ($fromDate !== null && $toDate !== null) {
            $builder->where('date_transaction >=', $fromDate)
                ->where('date_transaction <=', $toDate);
        }
        if ($transactionType !== null) {
            $builder->where('type_transaction', $transactionType);
        }
        if ($paymentStatus !== null) {
            $builder->where('payment_status', $paymentStatus);
        }

        return $builder->orderBy('date_transaction', 'desc')->get()->getResultArray();
    }

    public function show($id)
    {
        return $this->find($id);
    }

    public function getHutang($id = null)
    {
        $builder = $this->db->table('transaction');
        if (empty($id)) {
            $result = $builder->groupBy('user_id')->select("user_id")->selectSum('total_price')
                ->where('payment_status', 'unpaid')->where('type_transaction', 'pembelian')
                ->get()->getResultArray();
            return $result;
        } else {
            $result = $builder->groupBy('user_id')->select("user_id")
                ->where('payment_status', 'unpaid')->where('type_transaction', 'pembelian')
                ->where('user_id', $id)
                ->selectSum('total_price')->get()->getResultArray();
            return $result;
        }
    }
}
