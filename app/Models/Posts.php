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

    // Constants
    public const IMAGEPATH = 'media/article/';

    private function generateCoverImageUrl($coverImageName)
    {
        return base_url() . '/' . self::IMAGEPATH . $coverImageName;
    }

    public function getRecords($start, $length, $orderColumn, $orderDirection): array
    {
        $posts = $this->select('id, title, category, excerpt, cover')
            ->orderBy($orderColumn, $orderDirection)
            ->findAll($length, $start);

        foreach ($posts as $key => $post) {
            $posts[$key]['cover'] = $this->generateCoverImageUrl($posts[$key]['cover']);
        }

        return $posts;
    }

    public function getTotalRecords(): int
    {
        return $this->countAllResults() ?? 0;
    }

    public function getRecordSearch($search, $start, $length, $orderColumn, $orderDirection): array
    {
        $posts = $this->select('id, title, category, excerpt, cover')
            ->orderBy($orderColumn, $orderDirection)
            ->like('title', $search)
            ->orLike('category', $search)
            ->orLike('excerpt', $search)
            ->findAll($length, $start);

        foreach ($posts as $key => $post) {
            $posts[$key]['cover'] = $this->generateCoverImageUrl($posts[$key]['cover']);
        }

        return $posts;
    }

    public function getTotalRecordSearch($search): int
    {
        return $this->select('id, title, category, excerpt')
            ->like('title', $search)
            ->orLike('category', $search)
            ->orLike('excerpt', $search)
            ->countAllResults();
    }

    public function fetchValidationRules($options = []): array
    {
        return $rules = [
            'title' => 'required',
            'cover' => $options['cover'] ?? null . 'max_size[cover,1024]|is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png]',
            'category' => 'required',
            'content' => 'required',
        ];
    }
}
