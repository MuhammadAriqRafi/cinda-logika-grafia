<?php

namespace App\Models;

use App\Controllers\Backend\Interfaces\DatatableInterface;
use CodeIgniter\Model;

class Guestbook extends Model implements DatatableInterface
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
    protected $validationRules      = [
        'subject' => 'required',
        'name' => 'required',
        'email' => 'required|valid_email',
        'phone' => 'required',
        'message' => 'required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['addCurrentEpochTime'];
    protected $afterFind      = ['convertEpochTimeToDate'];

    public function getRecords($start, $length, $orderColumn, $orderDirection): array
    {
        return $this->select('id, subject, name, email, phone, created_at, status')
            ->orderBy($orderColumn, $orderDirection)
            ->findAll($length, $start);
    }

    public function getRecordSearch($search, $start, $length, $orderColumn, $orderDirection): array
    {
        return $this->select('id, subject, name, email, phone, created_at, status')
            ->orderBy($orderColumn, $orderDirection)
            ->like('subject', $search)
            ->orLike('name', $search)
            ->orLike('email', $search)
            ->orLike('phone', $search)
            ->orLike('message', $search)
            ->orLike('status', $search)
            ->findAll($length, $start);
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
}
