<?php

namespace App\View;

use App\entity\Comptes;
use App\entity\TypeDeCompte;
use App\entity\TypeDeTransaction;
use App\service\ComptesService;
use App\service\TransactionService;
use Exception;

class Main {

    private static ComptesService $comptesService;
    private static TransactionService $transactionService;

    public static function run(): void {
   
        try {
            self::$comptesService = new ComptesService();
            self::$transactionService = new TransactionService();

            $choix = null;

            do {
                self::afficherMenu();
                $choix = self::lireInt("Votre choix : ");

                switch ($choix) {
                    case 1:
                        self::creerCompte();
                        break;
                    case 2:
                        self::afficherComptes();
                        break;
                    case 3:
                        self::ajouterTransaction();
                        break;
                    case 4:
                        self::listerTransactions();
                        break;
                    case 0:
                        echo "Au revoir !\n";
                        break;
                    default:
                        echo "Choix invalide !\n";
                }
            } while ($choix != 0);
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage() . "\n";
        }
    }

    private static function afficherMenu(): void {
        echo "\n===== GESTION BANCAIRE =====\n";
        echo "1. Créer un compte\n";
        echo "2. Afficher les comptes\n";
        echo "3. Ajouter une transaction\n";
        echo "4. Lister les transactions d'un compte\n";
        echo "0. Quitter\n";
    }

    private static function creerCompte(): void {
        try {
           

            $typeChoisi = self::lireString("Choisir le type (Compte Epargne/Compte Cheque) : ");
            $type = TypeDeCompte::from($typeChoisi);
            $compte = new Comptes(type:$type);

            if ($type === TypeDeCompte::EPARGNE) {
                $duree = self::lireInt("Durée de blocage (en mois) : ");
                $compte->setDureeDeblocage($duree);
            }

            self::$comptesService->creatAcc($compte);
            echo "Compte créé avec succès !\n";
        } catch (Exception $e) {
            echo "Erreur lors de la création du compte: " . $e->getMessage() . "\n";
        }
    }

    private static function afficherComptes(): void {
        $comptes = self::$comptesService->searchAcc();

        if (empty($comptes)) {
            echo "Aucun compte trouvé.\n";
            return;
        }

        echo "\n--- LISTE DES COMPTES ---\n";
        foreach ($comptes as $c) {
            echo "Numéro: " . $c->getNumeroDeCompte() .
                 " | Type: " . $c->getType()->value .
                 " | Solde: " . $c->getSolde() . " FCFA\n";
        }
    }

    private static function ajouterTransaction(): void {
        try {
            $numero = self::lireString("Numéro de compte : ");

            $typeChoisi = self::lireString("Choisir le type (Entree/Sortie) : ");
            $type = TypeDeTransaction::from($typeChoisi);

            $montant = self::lireDouble("Montant : ");

            $ok = self::$transactionService->creatTransac($numero, $type, $montant);

            if ($ok) {
                echo "Transaction effectuée avec succès !\n";
            } else {
                echo "Échec de la transaction.\n";
            }
        } catch (Exception $e) {
            echo "Erreur lors de la transaction: " . $e->getMessage() . "\n";
        }
    }

    private static function listerTransactions(): void {
        $numero = self::lireString("Numéro de compte : ");

        $transactions = self::$transactionService->searchTransacByACC($numero);

        if (empty($transactions)) {
            echo "Aucune transaction trouvée.\n";
            return;
        }

        echo "\n--- TRANSACTIONS DU COMPTE " . $numero . " ---\n";
        foreach ($transactions as $t) {
            echo $t->getType()->value .
                 " | Montant: " . $t->getMontant() .
                 " | Frais: " . $t->getFrais() . "\n";
        }
    }

    private static function lireInt(string $msg): int {
        echo $msg;
        $input = trim(fgets(STDIN));
        return (int)$input;
    }

    private static function lireDouble(string $msg): float {
        echo $msg;
        $input = trim(fgets(STDIN));
        return (float)$input;
    }

    private static function lireString(string $msg): string {
        echo $msg;
        return trim(fgets(STDIN));
    }
}