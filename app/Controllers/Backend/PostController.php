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
            'indexCategoryUrl' => '/backend/categories/index',
            'ajaxIndexCategoryUrl' => '/backend/categories/ajaxIndex',
            'storeCategoryUrl' => '/backend/categories/store',
            'destroyCategoryUrl' => '/backend/categories/destroy/',
            'updateCategoryUrl' => '/backend/categories/update/',
            'editCategoryUrl' => '/backend/categories/edit/',
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
        $rawContent = $this->request->getVar('content');
        $content = $this->cleanseString($rawContent);
        $excerpt = $this->generateExcerpt($content);

        $data = [
            'cover' => '',
            'title' => $title,
            'excerpt' => $excerpt,
            'content' => $rawContent,
            'slug' => url_title($title, '-', true),
            'date' => date('Y-m-d h:i:s'),
            'category_id' => $this->request->getVar('category_id'),
            'file' => $this->request->getFile('cover'),
            'file_path' => Posts::IMAGEPATH,
            'file_context' => 'post',
            'generate_file_size_to' => 'thumb',
            'validation_options' => [
                'cover' => 'uploaded[cover]|'
            ]
        ];

        $this->setData($data);
        return parent::store();
    }

    public function edit($id = null)
    {
        $this->setData(['selected_fields' => 'title, category_id, content, cover']);
        return parent::edit($id);
    }

    public function update($id = null)
    {
        $title = $this->request->getVar('title');
        $rawContent = $this->request->getVar('content');
        $content = $this->cleanseString($rawContent);
        $excerpt = $this->generateExcerpt($content);
        $file = $this->request->getFile('cover');
        $oldImage = $this->model->select('cover')
            ->find($id)['cover'];

        $data = [
            'cover' => $oldImage,
            'id' => $id,
            'title' => $title,
            'excerpt' => $excerpt,
            'content' => $rawContent,
            'slug' => url_title($title, '-', true),
            'category_id' => $this->request->getVar('category_id'),
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

        // TODO: Also delete thumb image
        $this->setData($data);
        return parent::destroy($id);
    }
}
