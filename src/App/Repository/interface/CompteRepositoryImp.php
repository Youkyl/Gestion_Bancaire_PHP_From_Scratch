<?php

namespace App\repository\interface;

use App\entity\Comptes;

interface CompteRepositoryImp{
    
    public function insertCompte(Comptes $compte) : void;

    public function updateSoldeAcc(string $numeroDeCompte, $newSolde) : void;

    public function selectAll() : array;

    public function selectAccByNum(string $numeroDeCompte) : Comptes ;

    public function lastInserId() : int;
    public function count() : int;
}