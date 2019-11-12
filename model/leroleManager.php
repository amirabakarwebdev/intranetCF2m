<?php

class leroleManager
{

    private $db;

    public function __construct(MyPDO $connect)
    {
        $this->db = $connect;
    }

    public function displayContentLerole(): array
    {
        $sql = "
		DESCRIBE
			lerole;";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->execute();

        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectLerole(int $id): array
    {
        $sql = "
		SELECT
			*
		FROM
			lerole
		WHERE
			idlerole = :id;";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->bindParam(":id", $id, PDO::PARAM_INT);
        $sqlQuery->execute();

        return $sqlQuery->fetch(PDO::FETCH_ASSOC);
    }

    public function updateLeroleActif(lerole $lerole)
    {
        if (empty($lerole->getIdlerole()) || empty($lerole->getLintitule()) || empty($lerole->getLadescription())) {
            return false;
        }

        $sql = "UPDATE lerole SET Lintitule=?, Ladescription=?, actif=? WHERE idlerole=?;";

        $update = $this->db->prepare($sql);
        $update->bindValue(1, $lerole->getLintitule(), PDO::PARAM_STR);
        $update->bindValue(2, $lerole->getLadescription(), PDO::PARAM_STR);
        $update->bindValue(4, $lerole->getIdlerole(), PDO::PARAM_INT);
        $update->bindValue(3, $lerole->getActif(), PDO::PARAM_INT);

        try {

            $update->execute();
            return true;

        } catch (PDOException $e) {

            echo '<h2 style="color: red;">ERROR: ' . $e->getMessage() . '</h2>';
            return false;

        }
    }

    public function insertLerole(lerole $datas)
    {
        $sql = "
		INSERT INTO lerole(lintitule, ladescription)
		VALUES (?,?);";

        $sqlQuery = $this->db->prepare($sql);

        $sqlQuery->bindValue(1, $datas->getLintitule(), PDO::PARAM_STR);
        $sqlQuery->bindValue(2, $datas->getLadescription(), PDO::PARAM_STR);

        try {
            $sqlQuery->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getCode();
            return false;
        }

    }

    public function deleteLerole(int $id): void
    {
        $sql = "
		DELETE
		FROM
			lerole
		WHERE
			idlerole = :id;";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->bindParam(":id", $id, PDO::PARAM_INT);
        $sqlQuery->execute();
    }

    public function selectAllLerole(): array
    {
        $sql = "
		SELECT
			*
		FROM
			lerole
		";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->execute();

        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);

    }

    public function selectLeroleJoinLedroit(string $joinType = "inner"): array
    {
        $joinType = strtolower($joinType);
        if ($joinType !== "inner" && $joinType !== "left" && $joinType !== "right") {return [];}

        $sql = "
		SELECT
			lerole.idlerole AS lerole_idlerole, lerole.lintitule AS lerole_lintitule, lerole.ladescription AS lerole_ladescription, lerole_has_ledroit.lerole_idlerole AS lerole_has_ledroit_lerole_idlerole, lerole_has_ledroit.ledroit_idledroit AS lerole_has_ledroit_ledroit_idledroit, ledroit.idledroit AS ledroit_idledroit, ledroit.lintitule AS ledroit_lintitule, ledroit.ladescription AS ledroit_ladescription
		FROM
			lerole
		" . $joinType . " JOIN lerole_has_ledroit ON lerole.idlerole = lerole_idlerole
		" . $joinType . " JOIN ledroit ON ledroit.idledroit = ledroit_idledroit;";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->execute();

        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectLeroleJoinLutilisateur(string $joinType = "inner"): array
    {
        $joinType = strtolower($joinType);
        if ($joinType !== "inner" && $joinType !== "left" && $joinType !== "right") {return [];}

        $sql = "
		SELECT
			lerole.idlerole AS lerole_idlerole, lerole.lintitule AS lerole_lintitule, lerole.ladescription AS lerole_ladescription, lutilisateur_has_lerole.lutilisateur_idutilisateur AS lutilisateur_has_lerole_lutilisateur_idutilisateur, lutilisateur_has_lerole.lerole_idlerole AS lutilisateur_has_lerole_lerole_idlerole, lutilisateur.idlutilisateur AS lutilisateur_idlutilisateur, lutilisateur.lenomutilisateur AS lutilisateur_lenomutilisateur, lutilisateur.lemotdepasse AS lutilisateur_lemotdepasse, lutilisateur.lenom AS lutilisateur_lenom, lutilisateur.leprenom AS lutilisateur_leprenom, lutilisateur.lemail AS lutilisateur_lemail, lutilisateur.luniqueid AS lutilisateur_luniqueid
		FROM
			lerole
		" . $joinType . " JOIN lutilisateur_has_lerole ON lerole.idlerole = lerole_idlerole
		" . $joinType . " JOIN lutilisateur ON lutilisateur.idlutilisateur = lutilisateur_idutilisateur;";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->execute();

        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectRoleCountById(): int
    {
        $sql = "SELECT COUNT(idlerole) AS nb
			  FROM lerole";

        $sqlQuery = $this->db->query($sql);
        $recup = $sqlQuery->fetch(PDO::FETCH_ASSOC);
        return (int) $recup['nb'];

    }

    public function selectRoleWithLimit(int $page, int $nbParPage): array
    {

        $premsLIMIT = ($page - 1) * $nbParPage;
        $sql = "
		SELECT
			*
		FROM
			lerole
		LIMIT  ?, ?
		";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->bindValue(1, $premsLIMIT, PDO::PARAM_INT);
        $sqlQuery->bindValue(2, $nbParPage, PDO::PARAM_INT);
        $sqlQuery->execute();

        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);

    }

    public function roleSelectById(int $idlerole): array
    {
        if (empty($idlerole)) {
            return [];
        }

        $sql = "SELECT * FROM lerole WHERE idlerole = ? ;";
        $recup = $this->db->prepare($sql);

        $recup->bindValue(1, $idlerole, PDO::PARAM_INT);

        $recup->execute();

        if ($recup->rowCount() === 0) {
            return [];
        }
        return $recup->fetch(PDO::FETCH_ASSOC);
    }

    public function afficheStagiaireRole(): array
    {
        $sql = "SELECT le.idlerole,le.lintitule
FROM lerole le
INNER JOIN lutilisateur_has_lerole lu ON lu.lerole_idlerole=le.idlerole
INNER JOIN lutilisateur l ON l.idlutilisateur=lu.lutilisateur_idutilisateur
	";

        $recup = $this->db->query($sql);

        if ($recup->rowCount() === 0) {
            return [];
        }
        return $recup->fetchAll(PDO::FETCH_ASSOC);
    }

    public function SelectAllRoles()
    {
        $sql = "SELECT * FROM lerole ORDER BY lintitule ASC ";

        $recup = $this->db->query($sql);

        if ($recup->rowCount()) {
            return $recup->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

}
