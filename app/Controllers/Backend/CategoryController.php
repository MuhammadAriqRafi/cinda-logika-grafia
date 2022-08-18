<?php

namespace App\Controllers\Backend;

use App\Models\Category;

class CategoryController extends CRUDController
{
    public function __construct()
    {
        parent::__construct(new Category());
    }

    public function index()
    {
        $categories = $this->model->findAll();
        return $this->response->setJSON($categories);
    }
}
