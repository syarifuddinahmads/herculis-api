<?php

namespace App\Models;

use CodeIgniter\Model;

use function PHPUnit\Framework\isNull;

class RequestKuotaModel extends Model
{
    protected $table            = 'request_kuota';
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
        'status',
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

    public function index(
        $fromDate = null,
        $toDate = null,
        $paymentStatus = null,
        $status = null,
        $asongan_id = null
    ) {
        $builder = $this->db->table('request_kuota');

        if ($fromDate !== null && $toDate !== null) {
            $builder->where('date_requestKuota >=', $fromDate)
                ->where('date_requestKuota <=', $toDate);
        }
        if ($paymentStatus !== null) {
            $builder->where('status_payment', $paymentStatus);
        }
        if ($status !== null) {
            $builder->where('status', $status);
        }
        if ($asongan_id !== null) {
            $builder->where('user_id', $asongan_id);
        }

        return $builder->orderBy('date_requestKuota', 'desc')->get()->getResultArray();
    }
    public function getHutang($id = null)
    {
        $builder = $this->db->table('request_kuota');
        if (empty($id)) {
            $result = $builder->groupBy('user_id')->select("user_id")->selectSum('total')
                ->where('status_payment', 'unpaid')->where('status', 'approved')
                ->get()->getResultArray();
            return $result;
        } else {
            $result = $builder->groupBy('user_id')->select("user_id")
                ->where('user_id', $id)
                ->selectSum('total')
                ->where('status_payment', 'unpaid')->where('status', 'approved')
                ->get()->getResultArray();
            return $result;
        }
    }
    public function getCountRequest($date)
    {
        $builder = $this->db->table('request_kuota');
        $count = 0;
        $headers = $builder->where('Date(date_requestKuota) >=', $date)
            ->where('Date(date_requestKuota) <=', $date)->get()->getResultArray();
        // dd($headers);
        foreach ($headers as $data) {
            $builderDetail = $this->db->table('request_kuota_detail');
            $detail = $builderDetail->where('requestKuota_id =', $data['id'])
                ->get()->getResultArray();
            foreach ($detail as $value) {
                $count += $value['quantity'];
            }
        }
        return $count;
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
