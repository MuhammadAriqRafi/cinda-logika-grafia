<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Posts;
use Config\Services;
use PhpParser\Node\Expr\AssignOp\Pow;

class CRUDController extends BaseController
{
    protected object $model;
    protected $data;

    public function __construct($model)
    {
        $this->model = $model;
    }

    protected function setData($data): void
    {
        $this->data = $data;
    }

    private function getModelName(): string
    {
        return ucfirst($this->model->table);
    }

    private function getSelectedFields()
    {
        $selected_fields = $this->data['selected_fields'];
        $this->removeSelectedFieldsFromArrayData();
        return $selected_fields;
    }

    private function getValidationOptions()
    {
        $validations_options = $this->data['validation_options'];
        $this->removeValidationOptionsFromArrayData();
        return $validations_options;
    }

    private function getGenerateFileSizeTo()
    {
        $generate_file_size_to = $this->data['generate_file_size_to'];
        $this->removeGenerateFileSizeToFromArrayData();
        return $generate_file_size_to;
    }

    private function isFileSetInArrayData(): bool
    {
        if (array_key_exists('file', $this->data) || array_key_exists('file_old', $this->data)) return true;
        return false;
    }

    private function isSelectedFieldsSetInArrayData(): bool
    {
        if (array_key_exists('selected_fields', $this->data)) return true;
        return false;
    }

    private function isValidationOptionsSetInArrayData(): bool
    {
        if (array_key_exists('validation_options', $this->data)) return true;
        return false;
    }

    private function isGenerateFileSizeToSetInArrayData(): bool
    {
        if (array_key_exists('generate_file_size_to', $this->data)) return true;
        return false;
    }

    private function generateFileName(string $context, string $extension): string
    {
        helper('text');
        return date('YmjHis') . '_' . random_string('alnum', 4) . '_' . $context . '.' . $extension;
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

    private function storeFile(): bool
    {
        $file = $this->data['file'];
        $path = $this->data['file_path'];
        $context = $this->data['file_context'];
        $extension = $file->guessExtension();
        $storedFileName = $this->generateFileName($context, $extension);

        if ($file->move($path, $storedFileName)) {
            $this->data[array_key_first($this->data)] = $storedFileName;
            return true;
        };

        return false;
    }

    private function deletefile(): bool
    {
        $path = $this->data['file_path'];
        $oldFileName = $this->data['file_old'];
        $oldThumbFileName = 'thumb_' . $this->data['file_old'];

        try {
            unlink($path . $oldFileName);
            if (file_exists($path . $oldThumbFileName)) {
                unlink($path . $oldThumbFileName);
            }
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }

    private function generateFileSizeTo($size): bool
    {
        $thumbSize = Posts::THUMBSIZE;
        $imagePath = Posts::IMAGEPATH;
        $imageLib = Services::image();
        $uploadedFileName = $this->data[array_key_first($this->data)];

        switch ($size) {
            case 'thumb':
                $imageLib->withFile($imagePath . $uploadedFileName)
                    ->fit($thumbSize['width'], $thumbSize['height'], 'center')
                    ->save($imagePath . 'thumb_' . $uploadedFileName);
                return true;
            default:
                return false;
        }
    }

    private function removeFileFromArrayData(): void
    {
        unset($this->data['file']);
        unset($this->data['file_path']);
        unset($this->data['file_context']);
        unset($this->data['file_old']);
    }

    private function removeSelectedFieldsFromArrayData(): void
    {
        unset($this->data['selected_fields']);
    }

    private function removeValidationOptionsFromArrayData(): void
    {
        unset($this->data['validation_options']);
    }

    private function removeGenerateFileSizeToFromArrayData(): void
    {
        unset($this->data['generate_file_size_to']);
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

        // // ? Encode id
        // foreach ($list as $key => $value) {
        //     $list[$key][$this->model->primaryKey] = base64_encode($value[$this->model->primaryKey]);
        // }

        $response['data'] = $list;

        return $this->response->setJSON($response);
    }

    protected function store()
    {
        $rules = $this->isValidationOptionsSetInArrayData() ? $this->model->fetchValidationRules($this->getValidationOptions()) : $this->model->fetchValidationRules();
        if (!$this->validate($rules)) {
            return $this->response->setJSON($this->generateErrorMessageFrom($rules));
        }

        if ($this->isFileSetInArrayData()) {
            $this->storeFile();
            $this->removeFileFromArrayData();
            if ($this->isGenerateFileSizeToSetInArrayData()) $this->generateFileSizeTo($this->getGenerateFileSizeTo());
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

    protected function edit($id = null)
    {
        if ($this->isSelectedFieldsSetInArrayData()) {
            $post = $this->model->select($this->getSelectedFields())->find($id);
            return $this->response->setJSON($post);
        };

        return $this->response->setJSON($this->model->find($id));
    }

    protected function update()
    {
        $rules = $this->model->fetchValidationRules();
        if (!$this->validate($rules)) {
            return $this->response->setJSON($this->generateErrorMessageFrom($rules));
        }

        if ($this->isFileSetInArrayData()) {
            $this->deletefile();
            $this->storeFile();
            $this->removeFileFromArrayData();
        }

        $response = [
            'status' => true,
            'message' => $this->getModelName() . ' berhasil diubah'
        ];

        if ($this->model->save($this->data)) {
            return $this->response->setJSON($response);
        }

        $response['status'] = false;
        $response['message'] = $this->getModelName() . ' gagal diubah';
        return $this->response->setJSON($response);
    }

    protected function destroy($id = null)
    {
        $response = [
            'status' => true,
            'message' => $this->getModelName() . ' berhasil dihapus'
        ];

        if ($this->isFileSetInArrayData()) {
            $this->deleteFile();
            $this->removeFileFromArrayData();
        }

        if ($this->model->delete($id)) {
            return $this->response->setJSON($response);
        }

        $response['status'] = false;
        $response['message'] = $this->getModelName() . ' gagal dihapus';
        return $this->response->setJSON($response);
    }
}
