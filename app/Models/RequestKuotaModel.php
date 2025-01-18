<?php

namespace App\Models;

use CodeIgniter\Model;

class RequestKuotaModel extends Model
{
    protected $table            = 'requestkuota';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'user_id',
        'total',
        'note',
        'status_payment',
        'date_requestKuota'
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

    public function index($fromDate = null, $toDate = null, $paymentStatus = null)
    {
        $builder = $this->db->table('requestkuota');

        if ($fromDate !== null && $toDate !== null) {
            $builder->where('date_transaction >=', $fromDate)
                ->where('date_transaction <=', $toDate);
        }
        if ($paymentStatus !== null) {
            $builder->where('payment_status', $paymentStatus);
        }

        return $builder->orderBy('date_requestKuota', 'desc')->get()->getResultArray();
    }
    public function getRequestKuotaDetails($id)
    {
        return $this->db->table('request_kuota_detail')
            ->where('requestKuota_id', $id)
            ->get()->getResultArray();
    }
    public function show($id)
    {
        return $this->find($id);
    }
}
