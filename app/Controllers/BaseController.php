<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Kenjis\CI4Twig\Twig;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = [];
    protected $data = [];
    protected $twig;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->twig = new Twig(['cache' => false]);

        $this->data['sitename'] = "Cinda Logika Grafia";
        $this->data['sitedesc'] = "Delivering Integrated IT Solutions";
        $this->data['time'] = time();
        $this->data['uri_string'] = uri_string();
        session();
    }
}
