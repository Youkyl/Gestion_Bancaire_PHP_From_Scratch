<?php
namespace App\service;

use App\entity\Comptes;
use App\entity\TypeDeCompte;
use App\repository\ComptesRepository;


class ComptesService
{
    
    private ComptesRepository $comptesRepo;
   // private int $compteurNumero = 40;
    private static ComptesService | null $instance = null ;

    private function __construct()
    {
        $this->comptesRepo =  ComptesRepository::getInstance(); 
    }


    public static function getInstance(): ComptesService
    {
        if (self::$instance === null) {
            self::$instance = new ComptesService();
        }
        return self::$instance;
    }


    public function creatAcc(Comptes $compte) : void{
        
        $compte->setNumeroDeCompte($this->generateNumAcc());
        $this->comptesRepo->insertCompte($compte);
    }

    public function generateNumAcc(): string
    {
        $lastInsertId = $this->comptesRepo->lastInserId();

        $numero =  sprintf('CPT%05d', ++$lastInsertId);
        //dd($numero);
        return $numero;
    }

    public function initilizationSolde($numeroDeCompte, $montant) :void{
        $this->comptesRepo->updateSoldeAcc($numeroDeCompte, $montant);
    }

    public function ListTypeAcc(): array{

        return TypeDeCompte::cases();
        
    }

    public function searchAcc($limit = null, $offset = null) : array{
        return $this->comptesRepo->selectAll($limit, $offset);
    }

    public function searchAccByNum($numeroDeCompte) :Comptes{
        return $this->comptesRepo->selectAccByNum($numeroDeCompte);
    }

    public function getNumberOfAcc() : int{
        return $this->comptesRepo->count();
    }
    
}