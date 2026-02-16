<?php
namespace App\repository;

use App\core\Database;
use App\entity\Transaction;
use App\entity\TypeDeTransaction;
use App\repository\interface\TransactionRepositoryImp;
use Exception;
use PDO;
use PDOException;

class TransactionRepository implements TransactionRepositoryImp
{
 
    private static TransactionRepository | null $instance = null;
    
    private PDO $db;

    private function __construct()
    {
        $this->db = Database::getInstance();
    }

    public static function getInstance(): TransactionRepository
    {
        if (self::$instance === null) {
            self::$instance = new TransactionRepository();
        }
        return self::$instance;
    }

    public function insertTransaction(Transaction $transaction): void
    {
        try {
            $this->db->beginTransaction();
            error_log("✅ BEGIN transaction");
            
            // 1️⃣ INSERT transaction (SANS ::type_transaction)
            $sql = "
                INSERT INTO transaction (numero_compte, type, montant, frais)
                VALUES (:num, :type, :montant, :frais)
            ";
            
            $stmt = $this->db->prepare($sql);
            
            $params = [
                ':num' => $transaction->getCompte()->getNumeroDeCompte(),
                ':type' => $transaction->getType()->name,  // 'DEPOT' ou 'RETRAIT'
                ':montant' => $transaction->getMontant(),
                ':frais' => $transaction->getFrais()
            ];
            
            error_log("🔍 INSERT params: " . json_encode($params));
            
            $result = $stmt->execute($params);
            
            if (!$result) {
                $error = $stmt->errorInfo();
                error_log("❌ INSERT FAILED: " . json_encode($error));
                throw new Exception("INSERT échoué: " . ($error[2] ?? 'Unknown'));
            }
            
            error_log("✅ INSERT réussi");
            
            // 2️⃣ UPDATE compte (modification RELATIVE, pas absolue)
            $montantAjustement = $transaction->getType()->name === 'RETRAIT'
                ? -($transaction->getMontant() + $transaction->getFrais())
                : $transaction->getMontant();
            
            $sql = "
                UPDATE compte
                SET solde = solde + :ajustement
                WHERE numero_compte = :num
            ";
            
            $stmt = $this->db->prepare($sql);
            
            $params = [
                ':ajustement' => $montantAjustement,
                ':num' => $transaction->getCompte()->getNumeroDeCompte()
            ];
            
            error_log("🔍 UPDATE params: " . json_encode($params));
            
            $result = $stmt->execute($params);
            
            if (!$result) {
                $error = $stmt->errorInfo();
                error_log("❌ UPDATE FAILED: " . json_encode($error));
                throw new Exception("UPDATE échoué: " . ($error[2] ?? 'Unknown'));
            }
            
            if ($stmt->rowCount() === 0) {
                throw new Exception("Compte introuvable: " . $transaction->getCompte()->getNumeroDeCompte());
            }
            
            error_log("✅ UPDATE réussi (rows: " . $stmt->rowCount() . ")");
            
            $this->db->commit();
            error_log("✅ COMMIT réussi");
            
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
                error_log("🔄 ROLLBACK effectué");
            }
            error_log("❌ PDOException: " . $e->getMessage());
            error_log("❌ Code: " . $e->getCode());
            throw new Exception("Erreur lors de l'insertion de la transaction : " . $e->getMessage());
            
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
                error_log("🔄 ROLLBACK effectué");
            }
            error_log("❌ Exception: " . $e->getMessage());
            throw $e;
        }
    }

//     public function insertTransaction(Transaction $transaction) : void{

//           try 
//           {
//             $this->db->beginTransaction();
          

//         $sql = "
//             INSERT INTO transaction (numero_compte, type, montant, frais)
//             VALUES (:num, :type::type_transaction, :montant, :frais)
//         ";

//         $stmt = $this->db->prepare($sql);

//         $stmt->execute([
//             ':num' => $transaction->getCompte()->getNumeroDeCompte(),
//             ':type' => $transaction->getType()->name,
//             ':montant' => $transaction->getMontant(),
//             ':frais' => $transaction->getFrais()
//         ]);

//         $sql = "
//             UPDATE compte
//             SET solde = :solde
//             WHERE numero_compte = :num
//         ";

//         $stmt = $this->db->prepare($sql);   

//         $stmt->execute([
//             ':solde' => $transaction->getCompte()->getSolde(),
//             ':num' => $transaction->getCompte()->getNumeroDeCompte()
//         ]);
//         $this->db->commit();

//         // Transaction insérée avec succès

//         }   catch (Exception $e) {
//                 // Oups, problème ? On annule tout (rollback)
//                 $this->db->rollBack();
//                 throw new Exception("Erreur lors de l'insertion de la transaction : " . $e->getMessage());
//         }
// }


    public function selectTransaction(string $numeroDeCompte, $limit = null, $offset = null):array{


        $sql = "
            SELECT * FROM transaction
            WHERE numero_compte = :num
            ORDER BY date_transaction DESC
        ";


        $transactions = [];

        
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->db->prepare($sql);
        
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }

    
        $stmt->bindValue(':num', $numeroDeCompte, PDO::PARAM_STR);
        
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $transactions[] = new Transaction(
                 montant:$row['montant'],
                 type:TypeDeTransaction::fromDatabase($row['type']),
                 id: $row['id'],
                 frais:$row['frais'],
                 date:$row['date_transaction']
            );

           // $row = $stmt->fetch();
        }

        return $transactions;
    }

    public function selectAll($limit = null, $offset = null): array
    {
        //$stmt = $this->db->query("SELECT * FROM transaction");
        $sql = "SELECT * FROM transaction ORDER BY date_transaction DESC";
        $transactions = [];

        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->db->prepare($sql);
        
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $transactions[] = new Transaction(
                montant:$row['montant'],
                type:TypeDeTransaction::fromDatabase($row['type']),
                id: $row['id'],
                frais:$row['frais']
            );
        }

        return $transactions;
    }

    
public function countAllTransactions() : int {
    $sql = "SELECT COUNT(*) FROM transaction";
    return $this->db->query($sql)->fetchColumn();
}

/**
 * Compte le nombre de transactions pour chaque compte
 * @return array ['CPT00001' => 5, 'CPT00002' => 3, ...]
 */
public function countTransactionsByAccount(): array
{
    $sql = "
        SELECT numero_compte, COUNT(*) as total
        FROM transaction
        GROUP BY numero_compte
    ";
    
    $stmt = $this->db->query($sql);
    $result = [];
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result[$row['numero_compte']] = (int)$row['total'];
    }
    
    return $result;
}

}