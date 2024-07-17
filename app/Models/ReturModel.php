<?php

namespace App\Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class ReturModel extends Model
{
    protected $table            = 'retur';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'retur_code',
        'user_id',
        'staff_id',
        'retur_id',
        'publisher_id',
        'total_price',
        'date_retur',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

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

    public function getReturDetails($id)
    {
        return $this->db->table('retur_detail')
            ->where('retur_id', $id)
            ->get()->getResultArray();
    }
    
    public function last() {
        $builder = $this->db->table('retur');
        $builder->select('id, retur_code');
        $builder->whereIn('id', function(BaseBuilder $builder) {
            $builder->select('MAX(id)', false)
                    ->from('retur');
        });
        $query = $builder->get()->getRow();
        return $query;
    }

    public function index($fromDate = null, $toDate = null, $returType = null, $paymentStatus = null)
    {
        $builder = $this->db->table('retur');

        if ($fromDate !== null && $toDate !== null) {
            $builder->where('date_retur >=', $fromDate)
                    ->where('date_retur <=', $toDate);
        }
        if ($returType !== null) {
            $builder->where('retur_type', $returType);
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
