<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use Config\Services;

class CRUDController extends BaseController
{
    protected object $model;
    protected $data;

    public function __construct($model)
    {
        $this->model = $model;
    }

    private function generateErrorMessageFrom($rules): array
    {
        $validation = Services::validation();

        $data = [];
        $data['status'] = false;
        $data['error_input'] = [];

        foreach ($rules as $key => $rule) {
            if ($validation->hasError($key)) {
                $data['error_input'][] = [
                    'input_name' => $key,
                    'error_message' => $validation->getError($key)
                ];
            }
        }

        return $data;
    }

    private function getModelName(): string
    {
        return ucfirst($this->model->table);
    }

    protected function setData($data): void
    {
        $this->data = $data;
    }

    protected function isFileAttached(): bool
    {
        if (array_key_exists('file', $this->data)) return true;
        return false;
    }

    // CRUD
    /*
        ! The model should implement DatatableInterface
        ! The model should implement CRUDInterface
    */
    protected function index()
    {
        helper('utilities');
        $draw = $this->request->getVar('draw');
        $start = $this->request->getVar('start');
        $length = $this->request->getVar('length');
        $search = $this->request->getVar('search')['value'];
        $orderDirection = $this->request->getVar('order')[0]['dir'];
        $orderColumnIndex = $this->request->getVar('order')[0]['column'];
        $orderColumn = $this->request->getVar('columns')[$orderColumnIndex]['name'];
        $total = $this->model->getTotalRecords();

        // ? Preparing response
        $response = [
            'length' => $length,
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total
        ];

        // ? If client search something
        if ($search != '') {
            $list = $this->model->getRecordSearch($search, $start, $length, $orderColumn, $orderDirection);
            $total_search = $this->model->getTotalRecordSearch($search);
            $response['recordsFiltered'] = $total_search;
        } else $list = $this->model->getRecords($start, $length, $orderColumn, $orderDirection);

        // ? Encode id
        foreach ($list as $key => $value) {
            $list[$key][$this->model->primaryKey] = base64_encode($value[$this->model->primaryKey]);
        }

        $response['data'] = $list;

        return $this->response->setJSON($response);
    }

    protected function store()
    {
        $rules = $this->model->fetchValidationRules();
        if (!$this->validate($rules)) {
            return $this->response->setJSON($this->generateErrorMessageFrom($rules));
        }

        $response = [
            'status' => true,
            'message' => $this->getModelName() . ' berhasil ditambahkan'
        ];

        if ($this->model->save($this->data)) {
            return $this->response->setJSON($response);
        }

        $response['status'] = false;
        $response['message'] = $this->getModelName() . ' gagal ditambahkan';
        return $this->response->setJSON($response);
    }

    protected function destroy($id = null)
    {
        $response = [
            'status' => true,
            'message' => $this->getModelName() . ' berhasil dihapus'
        ];

        if ($this->model->delete($id)) {
            return $this->response->setJSON($response);
        }

        $response['status'] = false;
        $response['message'] = $this->getModelName() . ' gagal dihapus';
        return $this->response->setJSON($response);
    }
}
