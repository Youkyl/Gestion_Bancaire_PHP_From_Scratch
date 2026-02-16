<?php

namespace App\controllers;

use App\core\Controller;
use App\core\Route;
use App\core\Validator;
use App\entity\Comptes;
use App\entity\TypeDeCompte;
use App\http\Request;
use App\service\ComptesService;
use App\service\TransactionService;

class CompteController extends Controller
{
    private ComptesService $compteService;
    private TransactionService $transacServ;
    private Validator $validator;
    private static CompteController | null $instance = null;

    public function __construct()
    {
     $this->compteService = ComptesService::getInstance();
     $this->transacServ = TransactionService::getInstance();
    }

    public static function getInstance(): CompteController
    {
        if (self::$instance === null) {
            self::$instance = new CompteController();
        }
        return self::$instance;
    }
    
    // public function index()
    // {

    //     $page = $request->query->get("page", 1);
    //     $offset = (LIMIT_PAR_PAGE * $page) - LIMIT_PAR_PAGE;
    //     $count = $this->compteService->count();
    //     $nbrPage = ceil($count / LIMIT_PAR_PAGE);

    //     $comptes =  $this->compteService->searchAcc();
    //     $transactions = $this->transacServ->searchTransac();

    //     $nbrTransac = 0;

    //     foreach ($comptes as $compte) {
    //         $nbrTransac = ($this->transacServ->searchTransacByACC($compte->getNumeroDeCompte())) ;
            
            
    //         //$nbrTransac += $compte->getTransactions();
    //     }
    //     //dd($nbrTransac);

    //     $this->renderHtml('compte/index.html.php', [
    //         'comptes' => $comptes,
    //         'nbrTransac' => $nbrTransac,
    //         'nbrPage' => $nbrPage,
    //         'pageEnCours' => $page,
    //                                                 ]);
    // }


public function index(Request $request) {

    $page = max(1, (int)$request->get("page", 1)); 

    $limit = LIMIT_PAR_PAGE;
    $offset = ($page - 1) * $limit; 
    
    
    $totalComptes = $this->compteService->getNumberOfAcc();
    $nbrPage = ceil($totalComptes / $limit);
    
    
    $comptes = $this->compteService->searchAcc($limit, $offset);
    
    
    $nbrTransac = $this->transacServ->countTransacByAcc();
    
    $this->renderHtml('compte/index.html.php', [
        'comptes' => $comptes,
        'nbrTransac' => $nbrTransac,
        'nbrPage' => $nbrPage,
        'pageEnCours' => $page,
        'totalComptes' => $totalComptes,
    ]);
}

public function create()
    {
        $this->renderHtml('compte/create.html.php');
    }


public function store()
    {
    
        $data = $_POST; 

        $validator = Validator::getInstance($data);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          

            $validator->setRules([
                'type' => ['required', 'AccType'],
                'solde' => ['numeric']
            ]);

            $solde  = $_POST['solde'] ?? 0;
            $type   = $_POST['type'] ?? null;
            $dureeBlocage = $_POST['dureeBlocage'] ?? null;
            
            if (!$validator->passes()) {

                print_r($validator->errors());
                exit;
            } else{
                    $compte = new Comptes(
                    type:TypeDeCompte::fromDatabase($type),
                    solde:floatval($solde),
                    dureeDeblocage:$type === 'EPARGNE' ? intval($dureeBlocage) : null
                );
                 $this->compteService->creatAcc($compte);    
                 
                 $this->redirect('compte/index.html.php');
            }
            
        }
    }
    

}