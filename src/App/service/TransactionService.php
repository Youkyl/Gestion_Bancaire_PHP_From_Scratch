<?php
namespace App\service;

use App\entity\Transaction;
use App\entity\TypeDeTransaction;
use App\repository\TransactionRepository;
use DateTime;

class TransactionService
{
    

    private TransactionRepository $transactionRepo;
    private ComptesService $comptesService;
    private static TransactionService | null  $instance = null;

    private function __construct()
    {
        $this->transactionRepo = TransactionRepository::getInstance();
        $this->comptesService = ComptesService::getInstance();
    }

    public static function getInstance(): TransactionService
    {
        if (self::$instance === null) {
            self::$instance = new TransactionService();
        }
        return self::$instance;
    }

    public function creatTransac(string $numeroDeCompte, TypeDeTransaction $type, float $montant) : bool{


        $compte = $this->comptesService->searchAccByNum($numeroDeCompte);

        if ($compte === null) {
            return false; // Compte non trouvé
        }

        $frais = 0.00;
        $montantFinal = $montant;

        if ($type == TypeDeTransaction::RETRAIT) {

            if ($this->isBlockedEpargne($compte)){
                // Les retraits ne sont pas autorisés sur un compte épargne bloqué
                return false;
            }

            if ($compte->isCompteCheque()){

                $frais = $compte->getFraisTransaction($montant);
                $montantFinal += $frais;
                // Frais de transaction appliqués
            }

            if ($compte->getSolde() < $montantFinal) {
                // Solde insuffisant pour effectuer cette transaction
                return false;
            }

            $compte->setSolde($compte->getSolde()-$montantFinal) ;  
        }
        else {

                if ($compte->isCompteCheque()){

                    $frais = $compte->getFraisTransaction($montant);
                    $montantFinal -= $frais;
                    // Frais de transaction appliqués

                }
                
                $compte->setSolde($compte->getSolde()+$montantFinal) ;                

        }

        $transaction = new Transaction(
            montant: $montantFinal,
            type: $type,
            compte:  $compte,
            frais: $frais,
        );

        try {
            $this->transactionRepo->insertTransaction($transaction);
            return true;
        } catch (\Exception $e) {
            // Erreur lors de l'insertion en base de données
            return false;
        }
    }

    private function isBlockedEpargne($compte): bool
    {
        if (!$compte->isCompteEpargne()) {
            return false;
        }

        $duree = $compte->getDureeDeblocage();
        if ($duree === null || $duree <= 0) {
            return false;
        }

        if (method_exists($compte, 'getDateBlocage')) {
            $start = $compte->getDateBlocage();
        } elseif (method_exists($compte, 'getDateCreation')) {
            $start = $compte->getDateCreation();
        } else {
            $start = null;
        }

        if ($start) {
            $startDate = new DateTime($start);
            $endDate = (clone $startDate)->modify('+' . (int)$duree . ' months');
            return new DateTime() < $endDate;
        }

        return true;
    }

    public function listTypeTrans($numeroDeCompte): array{
        return TypeDeTransaction::cases();
    }


    public function searchTransacByACC($numeroDeCompte, $limit = null, $offset = null): array{
        return $this->transactionRepo->selectTransaction($numeroDeCompte, $limit, $offset);
    }

    public function searchTransac($limit = null, $offset = null): array{
        return $this->transactionRepo->selectAll($limit, $offset);
    }

    public function getNumberOfTransactions() : int{
        return $this->transactionRepo->countAllTransactions();
    }

    public function countTransacByAcc() : array{
        return $this->transactionRepo->countTransactionsByAccount();
    }
}