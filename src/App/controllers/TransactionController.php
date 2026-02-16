<?php

namespace App\controllers;

use App\core\Controller;
use App\entity\TypeDeCompte;
use App\entity\TypeDeTransaction;
use App\http\Request;
use App\service\ComptesService;
use App\service\TransactionService;

class TransactionController extends Controller
{
    private ComptesService $compteService;
    private TransactionService $transacServ;

    public function __construct()
    {
     $this->compteService = ComptesService::getInstance();
     $this->transacServ = TransactionService::getInstance();
    }

    public function index(Request $request)
    {
        $numeroDeCompte = $request->post("numeroDeCompte");  

        $comptes =  $this->compteService->searchAcc();
        $this->renderHtml('/transaction/index.html.php', [
            'comptes' => $comptes,
            'numeroDeCompte' => $numeroDeCompte
            ]);
    }

    public function create()
    {
        $comptes =  $this->compteService->searchAcc();
        $this->renderHtml('/transaction/creat.html.php', [
            'comptes' => $comptes
            ]);
    }

    //#[Route('/transaction/list.html.php/{numeroDeCompte}', methods:['GET'])]
   
    public function list( Request $request)
    {
        $page = max(1, (int)$request->get("page", 1)); 
        $numeroDeCompte = $request->get("numeroDeCompte");

        $limit = 5;
        $offset = ($page - 1) * $limit; 
    
    
        $totalTransactions = $this->transacServ->countTransacByAcc();
        $nbrTransac = is_array($totalTransactions) && isset($totalTransactions[$numeroDeCompte]) ? (int)$totalTransactions[$numeroDeCompte] : 0;

        $nbrPage = ceil($nbrTransac / $limit);

        $comptes =  $this->compteService->searchAccByNum($numeroDeCompte, $limit, $offset);
        $transactions = $this->transacServ->searchTransacByACC($numeroDeCompte, $limit, $offset);
       // dd( $transactions);
       // dd($comptes);
        $this->renderHtml('/transaction/list.html.php', [
            'comptes' => $comptes, 
            'transactions' => $transactions, 
            'numeroDeCompte' => $numeroDeCompte,
            'page' => $page, 
            'nbrPage' => $nbrPage
            ]);

    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $montant = $_POST['montant'] ?? null;
            $type   = $_POST['type'] ?? null;
            $numeroDeCompte = $_POST['numeroDeCompte'] ?? null;

            //dd( $montant, $type, $numeroDeCompte);

            
            $this->transacServ->creatTransac(
                montant:$montant, 
                type:TypeDeTransaction::fromDatabase($type),
                numeroDeCompte:$numeroDeCompte);    


            
            $this->redirect('transaction/list?numeroDeCompte=' . $numeroDeCompte);
             //$this->redirect('controller=home&action=index');
        }


    }
    

}
