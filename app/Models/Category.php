<?php

namespace App\Models;

use App\Controllers\Backend\Interfaces\CRUDInterface;
use App\Controllers\Backend\Interfaces\DatatableInterface;
use CodeIgniter\Model;

class Category extends Model implements DatatableInterface, CRUDInterface
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

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];

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

    public function fetchValidationRules(): array
    {
        return $rules = [
            'name' => 'required'
        ];
    }
}
