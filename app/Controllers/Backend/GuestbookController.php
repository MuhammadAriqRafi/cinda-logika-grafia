<?php

namespace App\Controllers\Backend;

use App\Models\Guestbook;

class GuestbookController extends CRUDController
{
    public function __construct()
    {
        parent::__construct(new Guestbook());
    }

    public function index()
    {
        $data = [
            'title' => 'Guestbook',
            'indexUrl' => '/backend/guestbooks/ajaxIndex',
            'storeUrl' => '/backend/guestbooks/store',
            'showUrl' => '/backend/guestbooks/show/',
            'updateUrl' => '/backend/guestbooks/update/',
            'destroyUrl' => '/backend/guestbooks/destroy/',
        ];

        return view('contents/backend/guestbooks/index', $data);
    }

    private function setGuestbookStatusToRead($id): void
    {
        $particularRecordStatus =
            $this->model->select('status')
                ->find($id)['status'];

        if ($particularRecordStatus == 'unread') $this->model->update($id, ['status' => 'read']);
    }

    public function ajaxIndex()
    {
        return parent::index();
    }

    public function show($id = null)
    {
        $this->setData(['selected_fields' => 'subject, name, email, message, created_at as date']);
        $response = json_decode(parent::show($id)->getJSON(), true);

        if ($response['status']) {
            $this->setGuestbookStatusToRead($id);
        }

        return $this->response->setJSON($response);
    }

    public function destroy($id = null)
    {
        return parent::destroy($id);
    }

    public function store()
    {
        $data = [
            'subject' => $this->request->getVar('subject'),
            'name' => $this->request->getVar('name'),
            'email' => $this->request->getVar('email'),
            'phone' => $this->request->getVar('phone'),
            'message'=> $this->request->getVar('message'),
            'created_at' => $this->generateCurrentEpochTime()
        ];

        $this->setData($data);
        return parent::store();
    }
}
