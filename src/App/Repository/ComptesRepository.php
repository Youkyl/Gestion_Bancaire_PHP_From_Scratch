<?php
namespace App\repository;

use App\core\Database;
use App\entity\Comptes;
use App\entity\TypeDeCompte;
use App\repository\interface\CompteRepositoryImp;
use PDO;

class ComptesRepository implements CompteRepositoryImp
{
    private static ComptesRepository | null $instance = null;
    
    private PDO $db;

    private function __construct()
    {
        $this->db = Database::getInstance();
    }


    public static function getInstance(): ComptesRepository
    {
        if (self::$instance === null) {
            self::$instance = new ComptesRepository();
        }
        return self::$instance;
    }


    public function insertCompte(Comptes $compte) : void{

   //dd($compte);
   //dd($this->db);

    try {
        $sql = "
            INSERT INTO compte (type, numero_compte, solde , duree_blocage ) 
            VALUES(:type, :numero , :solde, :duree);
        ";
        $numero = $compte->getNumeroDeCompte();
       // dd($numero);
       // dd($compte);
        //$numero = "CPT00028";

        $stmt = $this->db->prepare($sql);  
        //dd($stmt);

        $stmt->execute([
            ':numero' => $numero,
            ':type' => $compte->getType()->name,
            ':solde' => $compte->getSolde(),
            ':duree' => $compte->getDureeDeblocage()
        ]);
        //$stmt->execute();
    } catch (\PDOException $e) {
        throw new \Exception("Erreur lors de l'insertion du compte : " . $e->getMessage());

    }
        
 
        
    }


    
    public function selectAll($limit = null, $offset = null): array
    {
        
        // $stmt = $this->db->query("SELECT * FROM compte
        //     ORDER BY numero_compte ASC");

        $sql = "SELECT * FROM compte ORDER BY numero_compte ASC";
        $comptes = [];


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
            $comptes[] = new Comptes(
                numeroDeCompte: $row['numero_compte'],
                solde: floatval($row['solde']),
                type: TypeDeCompte::fromDatabase($row['type']),
                dureeDeblocage: $row['duree_blocage']
            );
        }

        return $comptes;
    }


    public function selectAccByNum(string $numeroCompte):Comptes {
        //var_dump($numeroCompte);
        //exit;
    
        $sql = "SELECT * FROM compte WHERE numero_compte = :num";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':num' => $numeroCompte]);

        $row = $stmt->fetch();

        if ($row) {
            return new Comptes(
                numeroDeCompte: $row['numero_compte'],
                solde: floatval($row['solde']),
                type: TypeDeCompte::fromDatabase($row['type']),
                dureeDeblocage: $row['duree_blocage']
            );
        }

        throw new \Exception("Compte non trouvé avec le numéro : " . $numeroCompte);
    }


    public function updateSoldeAcc( string $numeroDeCompte, $newSolde) : void {
        $sql = "
            UPDATE compte
            SET solde = :solde
            WHERE numero_compte = :num
        ";

        $stmt = $this->db->prepare($sql);   

        $stmt->execute([
            ':solde' => $newSolde,
            ':num' => $numeroDeCompte
        ]);

        throw new \Exception("Erreur lors de la mise à jour du solde pour le compte : " . $numeroDeCompte);
    }
    
    public function lastInserId(): int {
        $sql = "
            SELECT max(id) as max FROM compte
            ";

        $stmt = $this->db->prepare($sql);   
        $stmt->execute();
        $row = $stmt->fetch();
        return intval($row['max']);
    }


public function count() : int {
    $sql = "SELECT COUNT(*) FROM compte";
    return $this->db->query($sql)->fetchColumn();
}




}