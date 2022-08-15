<?php

namespace App\Controllers\Backend;

use App\Models\Posts;

class PostController extends CRUDController
{
    public function __construct()
    {
        parent::__construct(new Posts());
    }

    public function index()
    {
        $data = [
            'title' => 'Insights',
            'indexUrl' => '/backend/posts/ajaxIndex',
            'storeUrl' => '/backend/posts/store',
            'destroyUrl' => '/backend/posts/destroy/',
        ];

        return view('contents/backend/posts/index', $data);
    }

    public function ajaxIndex()
    {
        return parent::index();
    }

    public function store()
    {
        return $this->response->setJSON($this->request->getVar());
        $data = [
            'title' => $this->request->getVar('title'),
            'excerpt' => $this->request->getVar('excerpt'),
            'category' => $this->request->getVar('category'),
        ];

        $this->setData($data);
        dd($this->data);
    }

    public function destroy($id = null)
    {
        return parent::destroy();
    }
}
