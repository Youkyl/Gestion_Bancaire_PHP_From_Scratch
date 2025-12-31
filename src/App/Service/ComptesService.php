<?php
namespace App\Service;

use App\entity\Comptes;
use App\entity\TypeDeCompte;
use App\repository\ComptesRepository;
use ReflectionClass;

class ComptesService
{
    

    private ComptesRepository $comptesRepo;
    private $compteurNumero = 0;

    public function __construct()
    {
        $this->comptesRepo = new ComptesRepository();
    }


    public function creatAcc($compte) : void{
        
        $compte->setNumero($this->generateNumAcc());
        $this->comptesRepo->insertCompte($compte);
    }

    public function generateNumAcc(): string
    {
        return sprintf('CPT%05d', $this->compteurNumero++);
    }

    public function initilizationSolde($numeroDeCompte, $montant) :void{
        $this->comptesRepo->updateSoldeAcc($numeroDeCompte, $montant);
    }

    public function ListTypeAcc(): array{

        return TypeDeCompte::cases();
        
    }

    public function searchAcc() : array{
        return $this->comptesRepo->selectAll();
    }

    public function searchAccByNum($numeroDeCompte) :Comptes{
        return $this->comptesRepo->selectAccByNum($numeroDeCompte);
    }
}