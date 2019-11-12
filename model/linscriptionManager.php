<?php

class linscriptionManager
{

    private $db;

    public function __construct(MyPDO $connect)
    {
        $this->db = $connect;
    }

    public function displayContentLinscription(): array
    {
        $sql = "
		DESCRIBE
			linscription;";

		$sqlQuery = $this->db->prepare($sql);
		$sqlQuery->execute();
		
		return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	
public function selectLinscription(int $id): array {
	$sql = "
	SELECT
		*
	FROM
		linscription
	WHERE
		idlinscription = :id;";
	$sqlQuery = $this->db->prepare($sql);
	$sqlQuery->bindParam(":id", $id, PDO::PARAM_INT);
	$sqlQuery->execute();
	
	return $sqlQuery->fetch(PDO::FETCH_ASSOC);
}





    public function linscriptionDelete(int $id): void
    {
        $sql = "DELETE FROM linscription WHERE idlinscription=?";
        $req = $this->db->prepare($sql);
        $req->bindValue(1, $id, PDO::PARAM_INT);
        $req->execute();

    }

    public function linscriptionCreate(linscription $datas)
    {

        // vérification que les champs soient valides (pas vides)

        if (empty($datas->getDebut() || empty($datas->getFin() || empty($datas->getUtilisateur_idutilisateur() || empty($datas->getLasession_idsession()))))) {
            return false;
        }

        $sql = "INSERT INTO linscription (debut, fin, utilisateur_idutilisateur,lasession_idsession) VALUES(?,?,?,?);";

        $insert = $this->db->prepare($sql);

        $insert->bindValue(1, $datas->getDebut(), PDO::PARAM_STR);
        $insert->bindValue(2, $datas->getFin(), PDO::PARAM_STR);
        $insert->bindValue(3, $datas->getUtilisateur_idutilisateur(), PDO::PARAM_STR);
        $insert->bindValue(4, $datas->getLasession_idsession(), PDO::PARAM_STR);

        // gestion des erreurs avec try catch
        try {
            $insert->execute();
            return true;

        } catch (PDOException $e) {
            echo $e->getCode();
            return false;

        }

    }

    public function updateLinscription(int $id, array $datas)
    {
        $updateDatas = "";
        foreach ($datas as $dataField => $data) {
            $updateDatas .= $dataField . " = '" . $data . "', ";
        }
        $updateDatas = substr($updateDatas, 0, -2);

        $sql = "
	UPDATE
		linscription
	SET
		" . $updateDatas . "
	WHERE
		idlinscription = :id;";

	$sqlQuery = $this->db->prepare($sql);
	$sqlQuery->bindParam(":id", $id, PDO::PARAM_INT);
	$sqlQuery->execute();

	/*return $sqlQuery->fetch(PDO::FETCH_ASSOC);*/
}


public function insertLinscription(array $datas): void {
	$sql = " 
  INSERT INTO linscription(debut, fin, utilisateur_idutilisateur, lasession_idsession)
	VALUES
		(";
        foreach ($datas as $data) {
            $sql .= (gettype($data) == "string" ? "'" . $data . "'" : $data) . ", ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= ");";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->execute();
    }


// requete pour modifier linscription

public function linscriptionSelectByID(int $id): array {
	$sql ="
	SELECT i.idlinscription,i.debut,i.fin,l.lenomutilisateur,s.lenom,s.lacronyme,l.idlutilisateur,s.idlasession
                  FROM linscription i 
                  INNER JOIN lutilisateur l ON l.idlutilisateur = i.utilisateur_idutilisateur 
                  INNER JOIN lasession s ON s.idlasession = i.lasession_idsession
                  GROUP BY i.idlinscription 
				  ";
	$sqlQuery = $this->db->prepare($sql);
	$sqlQuery->bindParam(":id", $id, PDO::PARAM_INT);
	$sqlQuery->execute();

	return $sqlQuery->fetch(PDO::FETCH_ASSOC);
}

public function deleteLinscription(int $id): void {
	$sql = "
	DELETE
	FROM
		linscription
	WHERE
		idlinscription = :id;";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->bindParam(":id", $id, PDO::PARAM_INT);
        $sqlQuery->execute();
    }

    public function selectAllLinscription(): array
    {
        $sql = "
	SELECT
		*
	FROM
		linscription";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->execute();

        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
    }

    // sélection d'une inscription via son id
    public function inscriptionSelectById(int $idlinscription): array
    {
        if (empty($idlinscription)) {
            return [];
        }
        $sql = "SELECT * FROM linscription WHERE idlinscription = ? ;";
        $recup = $this->db->prepare($sql);
        $recup->bindValue(1, $idlinscription, PDO::PARAM_INT);
        $recup->execute();
        if ($recup->rowCount() === 0) {
            return [];
        }
        return $recup->fetch(PDO::FETCH_ASSOC);
    }

	
	public function inscriptionSelectAll_Actif(): array
    {
        $sql = "
	SELECT
		*
	FROM
		linscription
	WHERE actif = 1";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->execute();

        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
    }
	
	public function inscriptionSelectAll_NonActif(): array
    {
        $sql = "
	SELECT
		*
	FROM
		linscription
	WHERE actif = 0";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->execute();

        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
    }
	
	public function selectInscriptionCount_Actif(): int {

		$sql="SELECT COUNT(idlinscription) AS nb
		  FROM linscription
		  WHERE actif=1";
		  

		$sqlQuery = $this->db->query($sql);


		$recup = $sqlQuery->fetch(PDO::FETCH_ASSOC);
		return (int) $recup['nb'];


		$recup= $sqlQuery->fetch(PDO::FETCH_ASSOC);	  
		return (int) $recup['nb'];

	}

	public function inscriptionSelectAllWithLimit_Actif(int $pageInscription,int $nbParPageInscription): array{


		$premsLIMIT = ($pageInscription-1)*$nbParPageInscription;
		$sql = "
		SELECT
			*
		FROM
			linscription
		WHERE actif=1
		ORDER BY debut
		LIMIT  ?, ?
		";
		$sqlQuery = $this->db->prepare($sql);
		$sqlQuery->bindValue(1,$premsLIMIT,PDO::PARAM_INT);
		$sqlQuery->bindValue(2,$nbParPageInscription,PDO::PARAM_INT);
		$sqlQuery->execute();
		
		return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
		
	}

	public function inscriptionSelectAllWithLimit_NonActif(int $pageInscription,int $nbParPageInscription): array{


		$premsLIMIT = ($pageInscription-1)*$nbParPageInscription;
		$sql = "
		SELECT
			*
		FROM
			linscription
		WHERE actif=0
		ORDER BY debut
		LIMIT  ?, ?
		";
		$sqlQuery = $this->db->prepare($sql);
		$sqlQuery->bindValue(1,$premsLIMIT,PDO::PARAM_INT);
		$sqlQuery->bindValue(2,$nbParPageInscription,PDO::PARAM_INT);
		$sqlQuery->execute();
		
		return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
		
	}
	
	public function inscriptionDelete_NonActif(int $id): void {
		
		$sql = "
		DELETE
		FROM
		linscription
		WHERE
		idlinscription = :id AND actif = 0;";
		$sqlQuery = $this->db->prepare($sql);
		$sqlQuery->bindParam(":id", $id, PDO::PARAM_INT);
		$sqlQuery->execute();
		
    }
	

public function inscriptionAllSelectWithUser(int $page ,int $nbParPage ) : array {

    $premsLIMIT = ($page - 1) * $nbParPage;

    $sql ="SELECT i.idlinscription,i.debut,i.fin,l.lenomutilisateur,s.lenom,s.lacronyme,s.lacronyme
                  FROM linscription i 
                  INNER JOIN lutilisateur l ON l.idlutilisateur = i.utilisateur_idutilisateur 
                  INNER JOIN lasession s ON s.idlasession = i.lasession_idsession
                  LIMIT ?,?
                  ";
    $recup = $this->db->prepare($sql);
    $recup->bindValue(1,$premsLIMIT,PDO::PARAM_INT);
    $recup->bindValue(2,$nbParPage,PDO::PARAM_INT);
    $recup->execute();

    return $recup->fetchAll(PDO::FETCH_ASSOC);

  }
    public function selectLinscriptionCountById(): int
    {

        $sql = "SELECT COUNT(idlinscription) AS nb
		  FROM linscription";


        $sqlQuery = $this->db->query($sql);


        $recup = $sqlQuery->fetch(PDO::FETCH_ASSOC);
        return (int)$recup['nb'];
    }

}
