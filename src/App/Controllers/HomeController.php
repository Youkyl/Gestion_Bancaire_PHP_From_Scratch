<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->renderHtml(__DIR__ . '/../../App/View/index.html');
    }
}