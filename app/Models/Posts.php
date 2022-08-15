<?php

namespace App\Models;

use App\Controllers\Backend\Interfaces\CRUDInterface;
use App\Controllers\Backend\Interfaces\DatatableInterface;
use CodeIgniter\Model;

class Posts extends Model implements DatatableInterface, CRUDInterface
{
    protected $DBGroup          = 'default';
    protected $table            = 'posts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['date', 'title', 'slug', 'excerpt', 'content', 'category', 'cover'];

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
    protected $beforeUpdate   = [];

    public function getRecords($start, $length, $orderColumn, $orderDirection): array
    {
        return $this->select('id, title, category, excerpt')
            ->orderBy($orderColumn, $orderDirection)
            ->findAll($length, $start);
    }

    public function getTotalRecords(): int
    {
        return $this->countAllResults() ?? 0;
    }

    public function getRecordSearch($search, $start, $length, $orderColumn, $orderDirection): array
    {
        return $this->select('id, title, category, excerpt')
            ->orderBy($orderColumn, $orderDirection)
            ->like('title', $search)
            ->orLike('category', $search)
            ->orLike('excerpt', $search)
            ->findAll($length, $start);
    }

    public function getTotalRecordSearch($search): int
    {
        return $this->select('id, title, category, excerpt')
            ->like('title', $search)
            ->orLike('category', $search)
            ->orLike('excerpt', $search)
            ->countAllResults();
    }

    public function fetchValidationRules(): array
    {
        return $rules = [
            'title' => 'required',
            'excerpt' => 'required',
            'content' => 'required',
        ];
    }
}
