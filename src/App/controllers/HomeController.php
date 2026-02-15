<?php

namespace App\controllers;

use App\core\Controller;
use App\entity\Comptes;
use App\entity\TypeDeCompte;
use App\service\ComptesService;
use App\service\TransactionService;

class HomeController extends Controller
{
    private ComptesService $compteService;
    private TransactionService $transacServ;

    public function __construct()
    {
     $this->compteService = ComptesService::getInstance();
     $this->transacServ = TransactionService::getInstance();
    }   

    public function index()
    {
        
        $comptes =  $this->compteService->searchAcc();
        $transac = $this->transacServ->searchTransac();

        $totalEpargne = 0;
        $totalCheque =0;
        $totalSolde = 0;
        $comptesBloq = 0;
        foreach ($comptes as $compte) {
            $totalSolde += $compte->getSolde();
            if ($compte->getType()===TypeDeCompte::EPARGNE) {
                $totalEpargne ++;
            }
            else {
                $totalCheque ++;
            }

            if ($compte->getDureeDeblocage() != null) {
                $comptesBloq = $compte->getDureeDeblocage();
            }
        }       
        
        $this->renderHtml('/home/index.html.php', ['comptes' => $comptes,
                                                    'totalSolde' => $totalSolde,
                                                    'transac' => $transac,
                                                    'totalEpargne' => $totalEpargne,
                                                    'totalCheque' => $totalCheque,
                                                    'comptesBloq' => $comptesBloq]);
    }
}