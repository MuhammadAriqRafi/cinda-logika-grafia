<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Administrator;

class AuthController extends BaseController
{
    protected $administrator;

    public function __construct()
    {
        $this->administrator = new Administrator();
    }

    public function index()
    {
        $data = [
            'authenticateUrl' => '/backend/authenticate'
        ];

        return view('contents/backend/auth/index', $data);
    }

    private function validationRules()
    {
        return $rules = [
            'username' => 'required',
            'password' => [
                'rules' => 'required|validate[username,password]',
                'errors' => [
                    'validate' => 'Email or Password don\'t match'
                ]
            ]
        ];
    }

    public function authenticate()
    {
        $rules = $this->validationRules();
        if (!$this->validate($rules)) {
            return $this->response->setJSON(CRUDController::generateErrorMessageFrom($rules));
        }

        $response = [
            'status' => true,
            'message' => 'Login berhasil!',
            'data' => site_url() . 'backend/posts'
        ];

        $username = CRUDController::cleanseString($this->request->getVar('username'));
        $administrator = $this->administrator->select('username, id')
            ->where('username', $username)
            ->first();

        $data = [
            'session_id' => session_id(),
            'id' => $administrator['id'],
            'username' => $administrator['username'],
            'isLoggedIn' => true
        ];

        session()->set($data);
        return $this->response->setJSON($response);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->route('backend.login');
    }
}
