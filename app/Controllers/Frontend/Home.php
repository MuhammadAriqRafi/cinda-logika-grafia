<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function Index()
    {
        $this->data['sticky'] = "fixed-bottom";
        $this->data['title'] = $this->data['sitename'] . " | " . $this->data['sitedesc'];
        $this->data['desc'] = $this->data['sitename'] . ", " . $this->data['sitedesc'] . ".";
        return $this->twig->render("contents/frontend/home", $this->data);
    }

    public function About()
    {
        $this->data['title'] = "About Us | " . $this->data['sitename'];
        $this->data['desc'] = "Cinda Logika Grafia is a information technology company that provides solutions of problems and needs in the process of business.";
        return $this->twig->render("contents/frontend/about", $this->data);
    }

    public function Services()
    {
        $this->data['title'] = "Services | " . $this->data['sitename'];
        $this->data['desc'] = "From the invention to deployment and forward, we design and experience is very interesting.";
        return $this->twig->render("contents/frontend/services", $this->data);
    }

    public function Contact()
    {
        $this->data['title'] = "Contact Us | " . $this->data['sitename'];
        $this->data['desc'] = "Let us know what your craziest dream are, and we will do our best to make them come true.";
        $this->data['storeGuestbookUrl'] = '/backend/guestbooks/store';
        return $this->twig->render("contents/frontend/contact", $this->data);
    }

    public function Insight()
    {
        $this->data['title'] = "Insight | " . $this->data['sitename'];
        $this->data['desc'] = "We present to you many technology informations to support your mobilization.";
        $model = new \App\Models\Posts();
        $this->data['posts'] = $model->getRecords(0, 4, 'created_at', 'DESC');
        return $this->twig->render("contents/frontend/insight", $this->data);
    }

    public function Detail(string $slug = null)
    {
        $model = new \App\Models\Posts();
        $this->data['post'] = $model->where('slug', $slug)->first();
        $this->data['title'] = $this->data['post']['title'] . " | " . $this->data['sitename'];
        $this->data['desc'] = $this->data['post']['excerpt'];
        $this->data['posts'] = $model->getRecordsExcept(0, 3, $this->data['post']['id']);
        return $this->twig->render("contents/frontend/detail", $this->data);
    }
}
