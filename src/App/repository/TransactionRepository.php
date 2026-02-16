<?php
namespace App\repository;

use App\core\Database;
use App\entity\Transaction;
use App\entity\TypeDeTransaction;
use App\repository\interface\TransactionRepositoryImp;
use Exception;
use PDO;

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

        public function insertTransaction(Transaction $transaction) : void{

                error_log("🚀 DÉBUT insertTransaction pour compte: " . $transaction->getCompte()->getNumeroDeCompte());

                // Sécuriser l'état de la connexion au cas où une transaction précédente a échoué
                try {
                    $this->db->rollBack();
                    error_log("⚠️ Transaction précédente annulée avant nouveau BEGIN");
                } catch (\Throwable $e) {
                    // Pas de transaction active, on continue
                }
        
                    try 
                    {
                        $this->db->beginTransaction();
                        error_log("✅ Transaction SQL BEGIN réussie");
          
        // Étape 1 : INSERT transaction
        $sql = "
            INSERT INTO transaction (numero_compte, type, montant, frais)
            VALUES (:num, :type::type_transaction, :montant, :frais)
        ";

        $stmt = $this->db->prepare($sql);
        
        $params = [
            ':num' => $transaction->getCompte()->getNumeroDeCompte(),
            ':type' => $transaction->getType()->name,
            ':montant' => $transaction->getMontant(),
            ':frais' => $transaction->getFrais()
        ];
        
        error_log("🔍 INSERT transaction - Params: " . json_encode($params));
        
        $stmt->execute($params);
        if ($stmt->errorCode() !== '00000') {
            $errorInfo = $stmt->errorInfo();
            throw new \PDOException($errorInfo[2] ?? 'Erreur SQL inconnue', (int)($errorInfo[1] ?? 0));
        }
        error_log("✅ INSERT transaction réussi");

        // Étape 2 : UPDATE compte
        $sql = "
            UPDATE compte
            SET solde = :solde
            WHERE numero_compte = :num
        ";

        $stmt = $this->db->prepare($sql);   
        
        $updateParams = [
            ':solde' => $transaction->getCompte()->getSolde(),
            ':num' => $transaction->getCompte()->getNumeroDeCompte()
        ];
        
        error_log("🔍 UPDATE compte - Params: " . json_encode($updateParams));
        
        $stmt->execute($updateParams);
        if ($stmt->errorCode() !== '00000') {
            $errorInfo = $stmt->errorInfo();
            throw new \PDOException($errorInfo[2] ?? 'Erreur SQL inconnue', (int)($errorInfo[1] ?? 0));
        }
        error_log("✅ UPDATE compte réussi");
        
        $this->db->commit();
        error_log("✅ COMMIT transaction SQL réussi");

    }   catch (\PDOException $e) {
            // Exception PDO spécifique
            error_log("❌ ERREUR PDO: " . $e->getMessage());
            error_log("❌ Code erreur: " . $e->getCode());
            error_log("❌ Détails: " . print_r($e->errorInfo, true));
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
                error_log("🔄 ROLLBACK effectué");
            }
            throw new \Exception("Erreur SQL : " . $e->getMessage());
    }   catch (\Exception $e) {
            // Autres exceptions
            error_log("❌ ERREUR GÉNÉRALE: " . $e->getMessage());
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
                error_log("🔄 ROLLBACK effectué");
            }
            throw new \Exception("Erreur lors de l'insertion de la transaction : " . $e->getMessage());
    }
}


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