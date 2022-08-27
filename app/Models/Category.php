<?php

namespace App\Models;

use App\Controllers\Backend\Interfaces\DatatableInterface;
use CodeIgniter\Model;

class Category extends Model implements DatatableInterface
{
    protected $DBGroup          = 'default';
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'created_at'];

    // Validation
    protected $validationRules      = ['name' => 'required'];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['addCurrentEpochTime'];
    protected $afterFind      = ['convertEpochTimeToDate'];

    protected function generateCurrentEpochTime()
    {
        return round(microtime(true) * 1000);
    }

    protected function addCurrentEpochTime(array $data)
    {
        $data['data']['created_at'] = $this->generateCurrentEpochTime();
        return $data;
    }

    protected function convertEpochTimeToDate(array $responseData)
    {
        if ($responseData['data'] != null && array_key_exists('created_at', $responseData['data'])) {
            if (!isset($responseData['data'][0])) {
                $responseData['data']['created_at'] = date("Y-m-d H:i:s", substr($responseData['data']['created_at'], 0, 10));
                return $responseData;
            }

            foreach ($responseData['data'] as $key => $data) {
                $responseData['data'][$key]['created_at'] = date("Y-m-d H:i:s", substr($data['created_at'], 0, 10));
            }
        }

        return $responseData;
    }

    public function getRecords($start, $length, $orderColumn, $orderDirection): array
    {
        return $this->select('id, name')
            ->orderBy($orderColumn, $orderDirection)
            ->findAll($length, $start);
    }

    public function getRecordSearch($search, $start, $length, $orderColumn, $orderDirection): array
    {
        return $this->select('id, name')
            ->orderBy($orderColumn, $orderDirection)
            ->like('name', $search)
            ->findAll($length, $start);
    }

    public function getTotalRecords(): int
    {
        return $this->countAllResults() ?? 0;
    }

    public function getTotalRecordSearch($search): int
    {
        return $this->select('id, name')
            ->like('name', $search)
            ->countAllResults();
    }
}
