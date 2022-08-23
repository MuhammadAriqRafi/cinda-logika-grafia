<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
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
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        if ($username == 'admin' && $password == 'admin') {
            $data = [
                'session_id' => session_id(),
                'isLoggedIn' => true
            ];

            session()->set($data);

            return $this->response->setJSON(redirect()->route('backend.posts.index'));
        }

        return $this->response->setJSON(['ga masuk']);

        // $rules = $this->validationRules();
        // if (!$this->validate($rules)) {
        //     return redirect()->back()->withInput();
        // }

        // $administrator = $this->administrator->select('nama, admin_id')
        //     ->where('username', $this->request->getVar('username'))
        //     ->first();
        // $payload = [
        //     'session_id' => session_id(),
        //     'admin_id' => base64_encode($administrator['admin_id']),
        //     'nama' => $administrator['nama']
        // ];
        // $administratorNewStatus = [
        //     'admin_id' => $administrator['admin_id'],
        //     'status' => session_id()
        // ];

        // $jwt = $this->generateJwt($payload);

        // if ($this->administrator->save($administratorNewStatus)) return $this->response->setCookie('X-PPD-SESSION', $jwt, 10800, '', '', '', '', true, '')->redirect('/backend');
        // else redirect()->back();
    }

    // public function logout()
    // {
    //     $jwt = $this->request->getCookie('X-PPD-SESSION');
    //     $payload = $this->getJwtPayload($jwt);
    //     $administrator = $this->administrator->select('admin_id')
    //         ->find(base64_decode($payload->admin_id))['admin_id'];
    //     $administratorNewStatus = [
    //         'admin_id' => $administrator,
    //         'status' => null
    //     ];

    //     if ($this->administrator->save($administratorNewStatus)) return $this->response->setCookie('X-PPD-SESSION', '')->redirect('/backend/login');
    //     else redirect()->back();
    // }
}
