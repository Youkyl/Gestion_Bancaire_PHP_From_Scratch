<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Entity\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = [];
        $this->renderHtml(__DIR__ . '/../../App/View/Pages/ListTransac.html', ['transactions' => $transactions]);
    }

    public function create()
    {
        $this->renderHtml(__DIR__ . '/../../App/View/Pages/AddTransac.html');
    }

}
