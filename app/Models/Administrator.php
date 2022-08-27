<?php

namespace App\Models;

use CodeIgniter\Model;

class Administrator extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'administrators';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'username' => 'required',
        'password' => [
            'rules' => 'required|validate[username,password]',
            'errors' => [
                'validate' => 'Email or Password don\'t match'
            ]
        ]
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
