<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Entity\Comptes;
use App\Service\ComptesService;

class CompteController extends Controller
{
    Private ComptesService $compteService;

    public function __construct()
    {
        $this->compteService = new ComptesService();
    }
    
    public function index()
    {
        $comptes = [];
        //require_once __DIR__ . '/../../Public/Pages/ListerCompptes.html';
        $this->renderHtml(__DIR__ . '/../../App/View/Pages/ListerCompptes.html', ['comptes' => $comptes]);
    }

    public function create()
    {
        $this->renderHtml(__DIR__ . '/../../App/View/Pages/CreateAcc.html');
    }

    public function store()
    {

        var_dump($_POST);
        exit;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero = $_POST['numero'] ?? null;
            $solde  = $_POST['solde'] ?? 0;
            $type   = $_POST['type'] ?? null;
            $dureeBlocage = $_POST['dureeBlocage'] ?? null;

            $compte = new Comptes($numero, $solde, type:$type, dureeDeblocage:$dureeBlocage);
            
            $this->compteService->creatAcc($compte);    


            header('Location: index.php?action=comptes');
            exit;
        }
    }
    

}
