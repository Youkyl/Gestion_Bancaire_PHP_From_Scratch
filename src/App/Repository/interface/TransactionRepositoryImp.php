<?php

namespace App\repository\Interface;

use App\entity\Transaction;
use App\entity\TypeDeTransaction;

interface TransactionRepositoryImp{

    public function insertTransaction(Transaction $transaction) : void;

    public function selectTransaction(string $numeroDeCompte):array;

    public function selectAll(): array;

    public function countAllTransactions() : int;

}