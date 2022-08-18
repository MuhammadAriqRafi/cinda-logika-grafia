<?php

namespace App\Controllers\Backend;

use App\Models\Posts;

class PostController extends CRUDController
{
    public function __construct()
    {
        parent::__construct(new Posts());
    }

    // Utilities
    private function cleanseString($string): string
    {
        return trim(html_entity_decode(strip_tags($string)));
    }

    private function generateExcerpt($string): string
    {
        $delimiter = ' ';
        $words = explode($delimiter, $string);
        $excerpt = '';

        foreach ($words as $index => $word) {
            if ($index == 9) break;
            $excerpt .= $word . ' ';
        }

        return trim($excerpt . '...');
    }

    // CRUD
    public function index()
    {
        $data = [
            'title' => 'Insights',
            'indexUrl' => '/backend/posts/ajaxIndex',
            'storeUrl' => '/backend/posts/store',
            'destroyUrl' => '/backend/posts/destroy/',
            'updateUrl' => '/backend/posts/update/',
            'editUrl' => '/backend/posts/edit/',
            'indexCategory' => '/backend/categories/index',
        ];

        return view('contents/backend/posts/index', $data);
    }

    public function ajaxIndex()
    {
        return parent::index();
    }

    public function store()
    {
        $title = $this->request->getVar('title');
        $content = $this->cleanseString($this->request->getVar('content'));
        $excerpt = $this->generateExcerpt($content);

        $data = [
            'cover' => '',
            'title' => $title,
            'excerpt' => $excerpt,
            'content' => $content,
            'slug' => url_title($title, '-', true),
            'date' => date('Y-m-d h:i:s'),
            'category' => $this->request->getVar('category'),
            'file' => $this->request->getFile('cover'),
            'file_path' => Posts::IMAGEPATH,
            'file_context' => 'post',
            'validation_options' => [
                'cover' => 'uploaded[cover]|'
            ]
        ];

        $this->setData($data);
        return parent::store();
    }

    public function edit($id = null)
    {
        $this->setData(['selected_fields' => 'title, category, excerpt, cover']);
        return parent::edit($id);
    }

    public function update($id = null)
    {
        $title = $this->request->getVar('title');
        $content = $this->cleanseString($this->request->getVar('content'));
        $excerpt = $this->generateExcerpt($content);
        $file = $this->request->getFile('cover');
        $oldImage = $this->model->select('cover')
            ->find($id)['cover'];

        $data = [
            'cover' => $oldImage,
            'id' => $id,
            'title' => $title,
            'excerpt' => $excerpt,
            'content' => $content,
            'slug' => url_title($title, '-', true),
            'category' => $this->request->getVar('category'),
        ];

        if ($file->getError() != 4) {
            $file = [
                'file' => $file,
                'file_path' => Posts::IMAGEPATH,
                'file_context' => 'post',
                'file_old' => $oldImage,
            ];
            $data = array_merge($data, $file);
        }

        $this->setData($data);
        return parent::update();
    }

    public function destroy($id = null)
    {
        $oldImage = $this->model->select('cover')
            ->find($id)['cover'];

        $data = [
            'file_old' => $oldImage,
            'file_path' => Posts::IMAGEPATH
        ];

        $this->setData($data);
        return parent::destroy($id);
    }
}
