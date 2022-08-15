<?php

namespace App\Controllers\Backend;

class PostController extends CRUDController
{
    public function index()
    {
        return view('contents/backend/posts/index');
    }
}
