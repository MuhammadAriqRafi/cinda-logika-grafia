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
        return parent::index();
    }

    public function ajaxIndex()
    {
        $response = $this->model->select('id,name')->findAll();
        return $this->response->setJSON($response);
    }

    public function store()
    {
        $data = [
            'name' => $this->request->getVar('name'),
            'created_at' => $this->generateCurrentEpochTime()
        ];

        $this->setData($data);
        return parent::store();
    }

    public function edit($id = null)
    {
        $this->setData(['selected_fields' => 'name']);
        return parent::edit($id);
    }

    public function update($id = null)
    {
        $data = [
            'id' => $id,
            'name' => $this->request->getVar('name')
        ];

        $this->setData($data);
        return parent::update($id);
    }

    public function destroy($id = null)
    {
        return parent::destroy($id);
    }
}
