<?php

namespace App\Models;

use App\Controllers\Backend\Interfaces\CRUDInterface;
use App\Controllers\Backend\Interfaces\DatatableInterface;
use CodeIgniter\Model;

class Guestbook extends Model implements DatatableInterface, CRUDInterface
{
    protected $DBGroup          = 'default';
    protected $table            = 'guestbooks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['subject', 'name', 'email', 'phone', 'message', 'created_at', 'status'];

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

    private function convertEpochTimeToDate($epochTime)
    {
        return date("Y-m-d H:i:s", substr($epochTime, 0, 10));
    }

    public function getRecords($start, $length, $orderColumn, $orderDirection): array
    {
        $response = $this->select('id, subject, name, email, phone, created_at, status')
            ->orderBy($orderColumn, $orderDirection)
            ->findAll($length, $start);

        foreach ($response as $key => $value) {
            $response[$key]['created_at'] = $this->convertEpochTimeToDate($value['created_at']);
        }

        return $response;
    }

    public function getRecordSearch($search, $start, $length, $orderColumn, $orderDirection): array
    {
        $response = $this->select('id, subject, name, email, phone, created_at, status')
            ->orderBy($orderColumn, $orderDirection)
            ->like('subject', $search)
            ->orLike('name', $search)
            ->orLike('email', $search)
            ->orLike('phone', $search)
            ->orLike('message', $search)
            ->orLike('status', $search)
            ->findAll($length, $start);

        foreach ($response as $key => $value) {
            $response[$key]['created_at'] = $this->convertEpochTimeToDate($value['created_at']);
        }

        return $response;
    }

    public function getTotalRecords(): int
    {
        return $this->countAllResults() ?? 0;
    }

    public function getTotalRecordSearch($search): int
    {
        return $this->select('id, subject, name, email, phone, message, status')
            ->like('subject', $search)
            ->orLike('name', $search)
            ->orLike('email', $search)
            ->orLike('phone', $search)
            ->orLike('message', $search)
            ->orLike('status', $search)
            ->countAllResults();
    }

    public function fetchValidationRules(): array
    {
        return $rules = [
            'subject' => 'required',
            'name' => 'required',
            'email' => 'required|valid_email',
            'phone' => 'required',
            'message' => 'required',
        ];
    }
}
