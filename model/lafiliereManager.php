<?php
class lafiliereManager {

    private $db;

    /*
     *
     * METHODES COMMUNES
     *
     */

    // constructeur
    public function __construct(MyPDO $connect) {
        $this->db = $connect;
    }

    // création d'une nouvelle filière
    public function filiereCreate(lafiliere $datas) {

        $sql = "INSERT INTO lafiliere (lenom, lacronyme, lacouleur, lepicto) VALUES (?,?,?,?);";
        $insert = $this->db->prepare($sql);
        $insert->bindValue(1, $datas->getLenom(), PDO::PARAM_STR);
        $insert->bindValue(2, $datas->getLacronyme(), PDO::PARAM_STR);
        $insert->bindValue(3, $datas->getLacouleur(), PDO::PARAM_STR);
        $insert->bindValue(4, $datas->getLepicto(), PDO::PARAM_STR);

        try {
            $insert->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getCode();
            return false;
        }

    }


    /*
     *
     * METHODES POUR ROLES ADMIN
     *
     */

    // infos sur lafiliere
    public function displayContentLafiliere(): array {
        $sql = "
		DESCRIBE
			lafiliere;";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->execute();
        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
    }

    // sélection de toutes les filières
    public function filiereSelectAll(): array {
        $sql = "SELECT * FROM lafiliere  ;";
        $recup = $this->db->query($sql);
        if ($recup->rowCount() === 0) {
            return [];
        }
        return $recup->fetchAll(PDO::FETCH_ASSOC);
    }

    // sélection d'une filière via son id
    public function filiereSelectById(int $idlafiliere): array {
        if (empty($idlafiliere)) {
            return[];
        }
        $sql = "SELECT * FROM lafiliere WHERE idlafiliere = ? ;";
        $recup = $this->db->prepare($sql);
        $recup->bindValue(1, $idlafiliere, PDO::PARAM_INT);
        $recup->execute();
        if ($recup->rowCount() === 0) {
            return [];
        }
        return $recup->fetch(PDO::FETCH_ASSOC);
    }

        
    // mise à jour de filière
    public function filiereUpdate(lafiliere $datas, int $get) {

        if (empty($datas->getlenom()) || empty($datas->getlacronyme()) || empty($datas->getidlafiliere()) || empty($datas->getLacouleur())) {
            return false;
        }
        $sql = "UPDATE lafiliere SET lenom=?, lacronyme=?, lacouleur=?, lepicto=?, actif=? WHERE idlafiliere=?;";
        $update = $this->db->prepare($sql);
        $update->bindValue(1, $datas->getLenom(), PDO::PARAM_STR);
        $update->bindValue(2, $datas->getLacronyme(), PDO::PARAM_STR);
        $update->bindValue(3, $datas->getLacouleur(), PDO::PARAM_STR);
        $update->bindValue(4, $datas->getLepicto(), PDO::PARAM_STR);
		$update->bindValue(5, $datas->getActif(), PDO::PARAM_INT);
        $update->bindValue(6, $datas->getIdlafiliere(), PDO::PARAM_INT);
        
        try {
            $update->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getCode();
            return false;
        }
    }

    // suppression de la filière
    public function filiereDelete(int $idlafiliere) {
        $sql = "DELETE FROM lafiliere WHERE idlafiliere=?";
        $delete = $this->db->prepare($sql);
        $delete->bindValue(1, $idlafiliere, PDO::PARAM_INT);
        try {
            $delete->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getCode();
            return false;
        }
    }


    // nombre total de filières
    public function selectFiliereCount(): int {

		$sql="SELECT COUNT(idlafiliere) AS nb
			  FROM lafiliere";

         $sqlQuery = $this->db->query($sql);

         $recup = $sqlQuery->fetch(PDO::FETCH_ASSOC);
         return (int) $recup['nb'];

    }
    
    // sélection des filières avec pagination
    public function selectFiliereWithLimit(int $pageFiliere,int $nbParPageFiliere): array{

	    $premsLIMIT = ($pageFiliere-1)*$nbParPageFiliere;
		$sql = "
		SELECT
			*
		FROM
			lafiliere
		LIMIT  ?, ?
		";
		$sqlQuery = $this->db->prepare($sql);
		$sqlQuery->bindValue(1,$premsLIMIT,PDO::PARAM_INT);
		$sqlQuery->bindValue(2,$nbParPageFiliere,PDO::PARAM_INT);
		$sqlQuery->execute();
		
		return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);

	}

	/*
	 *
	 * METHODES POUR ROLE PEDAGOGIQUE
	 *
	 */

	// sélection de toutes les filières actives
    public function filiereSelectAllActif(): array {
        $sql = "SELECT * FROM lafiliere WHERE actif=1 ;";
        $recup = $this->db->query($sql);
        if ($recup->rowCount() === 0) {
            return [];
        }
        return $recup->fetchAll(PDO::FETCH_ASSOC);
    }

    // sélection d'une filière via son id si elle est active
    public function filiereSelectByIdActif(int $idlafiliere): array {
        if (empty($idlafiliere)) {
            return[];
        }
        $sql = "SELECT * FROM lafiliere WHERE idlafiliere = ? AND actif=1 ;";
        $recup = $this->db->prepare($sql);
        $recup->bindValue(1, $idlafiliere, PDO::PARAM_INT);
        $recup->execute();
        if ($recup->rowCount() === 0) {
            return [];
        }
        return $recup->fetch(PDO::FETCH_ASSOC);
    }

    // update d'une filière si active
    public function filiereUpdateActif(lafiliere $datas, int $get) {

        if (empty($datas->getlenom()) || empty($datas->getlacronyme()) || empty($datas->getidlafiliere()) || empty($datas->getLacouleur())) {
            return false;
        }
        $sql = "UPDATE lafiliere SET lenom=?, lacronyme=?, lacouleur=?, lepicto=? WHERE idlafiliere=? AND actif=1;";
        $update = $this->db->prepare($sql);
        $update->bindValue(1, $datas->getLenom(), PDO::PARAM_STR);
        $update->bindValue(2, $datas->getLacronyme(), PDO::PARAM_STR);
        $update->bindValue(3, $datas->getLacouleur(), PDO::PARAM_STR);
        $update->bindValue(4, $datas->getLepicto(), PDO::PARAM_STR);
        $update->bindValue(5, $datas->getIdlafiliere(), PDO::PARAM_INT);

        try {
            $update->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getCode();
            return false;
        }
    }

    // Fausse suppression pour les non admin, passage d'actif à 0
    public function filiereDeleteActif(int $idlafiliere) {
        $sql = "UPDATE lafiliere SET actif=0 WHERE idlafiliere=?";
        $delete = $this->db->prepare($sql);
        $delete->bindValue(1, $idlafiliere, PDO::PARAM_INT);
        try {
            $delete->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getCode();
            return false;
        }
    }

    // On compte le nombre de filières actives
    public function selectFiliereCountActif(): int {

        $sql="SELECT COUNT(idlafiliere) AS nb
			  FROM lafiliere WHERE actif=1";

        $sqlQuery = $this->db->query($sql);

        $recup = $sqlQuery->fetch(PDO::FETCH_ASSOC);
        return (int) $recup['nb'];

    }

    // Sélection des articles actifs avec LIMIT
    public function selectFiliereWithLimitActif(int $pageFiliere,int $nbParPageFiliere): array{

        $premsLIMIT = ($pageFiliere-1)*$nbParPageFiliere;
        $sql = "
		SELECT
			*
		FROM
			lafiliere
		WHERE actif=1
		LIMIT  ?, ?
		";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->bindValue(1,$premsLIMIT,PDO::PARAM_INT);
        $sqlQuery->bindValue(2,$nbParPageFiliere,PDO::PARAM_INT);
        $sqlQuery->execute();

        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);

    }
    
}