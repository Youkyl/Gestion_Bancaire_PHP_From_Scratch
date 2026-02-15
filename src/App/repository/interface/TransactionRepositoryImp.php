<?php

namespace App\repository\interface;

use App\entity\Transaction;
use App\entity\TypeDeTransaction;

interface TransactionRepositoryImp{

    public function insertTransaction(Transaction $transaction) : void;

    public function selectTransaction(string $numeroDeCompte):array;

    public function selectAll(): array;

    public function countAllTransactions() : int;

}