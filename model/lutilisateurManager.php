<?php
class lutilisateurManager {

    private $db;
    public function __construct(MyPDO $connect) {
        $this->db = $connect;
    }
    /*
     * Connexion
     */
    public function connectLutilisateur(lutilisateur $user): bool {
        /*
         * Query to take an user
         */
        $sql = "SELECT lutilisateur.idlutilisateur, lutilisateur.lenomutilisateur, lutilisateur.lenom, lutilisateur.lemotdepasse AS pwd ,lutilisateur.leprenom, lutilisateur.lemail, lutilisateur.luniqueid, 
            lerole.idlerole, lerole.lintitule, lerole.ladescription
		FROM 
                    lutilisateur
		LEFT JOIN 
                    lutilisateur_has_lerole ON lutilisateur.idlutilisateur = lutilisateur_has_lerole.lutilisateur_idutilisateur
		LEFT JOIN 
                    lerole ON lerole.idlerole = lutilisateur_has_lerole.lerole_idlerole
		WHERE lutilisateur.lenomutilisateur = :lenomutilisateur 
		LIMIT 1;"; // LIMIT 1 tant qu'un utilisateur ne peut avoir qu'un rôle (many to one artificiel, car la db permet le many to many)
        // on récupère l'utilisateur si valide par le champs "lenomutilisateur" sans vérifier le mot de passe
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->bindValue(":lenomutilisateur", $user->getLenomutilisateur(), PDO::PARAM_STR);
        $sqlQuery->execute();
        $result = $sqlQuery->fetch(PDO::FETCH_ASSOC);
        // si le mot de passe est valide, on vérifie le mot de passe crypté avec password_hash(mot_de_passe, PASSWORD_DEFAULT), soit décrypté et valide avec la fonction password_verify(mdp_formulaire, mdp_db))
        if ($user->getLenomutilisateur() == $result['lenomutilisateur'] && password_verify($user->getLemotdepasse(), $result['pwd'])) {
            // si ok création de la session qui contient tous les champs des 2 tables sélectionnées lors de la connexion, + la session_id, - pwd qu'on ne souhaite pas garder dans e mdp dans la session, utilisation de unset qui détruit la variable $_SESSION['pwd']
            $_SESSION = $result;
            $_SESSION['TheIdSess'] = session_id();
            unset($_SESSION['pwd']);
            return True;
        } else {
            return False;
        }
    }
    public function lutilisateurDisplayContent(): array {
        $sql = "DESCRIBE lutilisateur;";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->execute();
        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
    }
    public function lutilisateurSelectById(lutilisateur $user): array {
        $sql = "
		SELECT
			*
		FROM
			lutilisateur
		WHERE
			idlutilisateur = :id;";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->bindValue(":id", $user->getIdlutilisateur(), PDO::PARAM_INT);
        $sqlQuery->execute();
        return $sqlQuery->fetch(PDO::FETCH_ASSOC);
    }
    public function lutilisateurUpdate(lutilisateur $user, array $datas) {
        $updateDatas = "";
        foreach ($datas as $dataField => $data) {
            $updateDatas .= $dataField . " = '" . $data . "', ";
        }
        $updateDatas = substr($updateDatas, 0, -2);
        $sql = "
		UPDATE
			lutilisateur
		SET
			" . $updateDatas . "
		WHERE
			idlutilisateur = :id;";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->bindValue(":id", $user->getIdlutilisateur(), PDO::PARAM_INT);
        $sqlQuery->execute();
    }
    public function lutilisateurDelete(lutilisateur $user): void {
        $sql = "
		DELETE
		FROM
			lutilisateur
		WHERE
			idlutilisateur = :id;";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->bindValue(":id", $user->getIdlutilisateur(), PDO::PARAM_INT);
        $sqlQuery->execute();
    }
    public function lutilisateurSelectAll(): array {
        $sql = "
		SELECT
			*
		FROM
			lutilisateur";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->execute();
        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
    }
    public function lutilisateurSelectAllJoinLerole(string $joinType = "inner"): array {
        $joinType = strtolower($joinType);
        if ($joinType !== "inner" && $joinType !== "left" && $joinType !== "right") {
            return [];
        }
        $sql = "
		SELECT
			lutilisateur.idlutilisateur AS lutilisateur_idlutilisateur, lutilisateur.lenomutilisateur AS lutilisateur_lenomutilisateur, lutilisateur.lemotdepasse AS lutilisateur_lemotdepasse, lutilisateur.lenom AS lutilisateur_lenom, lutilisateur.leprenom AS lutilisateur_leprenom, lutilisateur.lemail AS lutilisateur_lemail, lutilisateur.luniqueid AS lutilisateur_luniqueid, lutilisateur_has_lerole.lutilisateur_idutilisateur AS lutilisateur_has_lerole_lutilisateur_idutilisateur, lutilisateur_has_lerole.lerole_idlerole AS lutilisateur_has_lerole_lerole_idlerole, lerole.idlerole AS lerole_idlerole, lerole.lintitule AS lerole_lintitule, lerole.ladescription AS lerole_ladescription
		FROM
			lutilisateur
		" . $joinType . " JOIN lutilisateur_has_lerole ON lutilisateur.idlutilisateur = lerole_idlerole
		" . $joinType . " JOIN lerole ON lerole.idlerole = lutilisateur_idutilisateur;";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->execute();
        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
    }




    //create a new user
    public function lutilisateurCreate( lutilisateur $user,int $role) {
		
	if(empty($user->getLenom()) ||empty($user->getLeprenom()) ||empty($user->getLemail())){
	  return false;
    }
	
	$sql = "SELECT * FROM lutilisateur WHERE lemail = ?";
	$check = $this->db->prepare($sql);
	$check->bindvalue(1, $user->getLemail(), PDO::PARAM_STR);
	
	try {
		$check->execute();
		$mail = $check->fetch(PDO::FETCH_ASSOC);
	} catch(PDOException $e) {
		echo $e->getCode();
        return false;
	}
	
	if($check->rowCount()) {
		echo "<h2 style='color: red;'>Ce mail est déjà utilisé. Veuillez saisir un autre mail.</h2>";
		return false;
	}
	
    $sql = "INSERT INTO lutilisateur ( lenomutilisateur, lemotdepasse, lenom, leprenom, lemail, luniqueid) VALUES(?,?,?,?,?,?);";
    $insert = $this->db->prepare($sql);
    // création de l'uniqueid et cryptage du mot de passe (1234 par défaut)
    $user->setLuniqueid();
    $user->setLemotdepasseCrypte(1234);


    $insert->bindvalue(1, $user->getLenomutilisateur(),PDO::PARAM_STR);
    $insert->bindvalue(2, $user->getLemotdepasse(),PDO::PARAM_STR);
    $insert->bindvalue(3, $user->getLenom(),PDO::PARAM_STR);
    $insert->bindvalue(4, $user->getLeprenom(),PDO::PARAM_STR);
    $insert->bindvalue(5, $user->getLemail(),PDO::PARAM_STR);
    $insert->bindvalue(6, $user->getLuniqueid(),PDO::PARAM_STR);
	
    //gestion des erreurs avec try catch
    try {
        $insert->execute();
    } catch(PDOException $e) {
        echo $e->getCode();
        return false;
    }

	$idutilisateur = $this->db->lastInsertId();

	$sql = "INSERT INTO  lutilisateur_has_lerole
	(lutilisateur_idutilisateur,lerole_idlerole) VALUES ($idutilisateur,?)";
	$req = $this->db->prepare($sql);
	$req->bindValue(1, $role, PDO::PARAM_INT);
		try {
			$req->execute();
			return true;
		} catch(PDOException $e) {
			echo $e->getCode();
			return false;
		}
		
    }


   

    // methode de deconnexion
    public function disconnectLutilisateur() {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: ./");
    }
  
    public function motDePasseOublier(lutilisateur $user){
        $sql="UPDATE lutilisateur SET lemotdepasse = ?  WHERE idlutilisateur = ?;";
        $insert = $this->db->prepare($sql);
        $insert->bindvalue(1, $user->getLemotdepasse(),PDO::PARAM_STR);
        $insert->bindvalue(2, $user->getIdlutilisateur(),PDO::PARAM_STR);
        try{
            $insert->execute();
            return true;
        }catch(PDOException $e){
            echo $e->getCode();
            return false;
        }
    }
    public function checkMail( string $mail){
        $sql ="SELECT * FROM lutilisateur WHERE lemail = ? ";
        $result = $this->db->prepare($sql);
        $result->bindvalue(1, $mail,PDO::PARAM_STR);
        //gestion des erreurs avec try catch
        try{
            $result->execute();
            return $result->rowCount()==1 ? true : false;
        }catch(PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }




    public function selectlutilisateurWithLimit(int $page, int $nbParPage): array
    {


        $premsLIMIT = ($page - 1) * $nbParPage;
        $sql = "SELECT lutilisateur.* ,lerole.lintitule 
             FROM lutilisateur
          INNER JOIN lutilisateur_has_lerole
            on lutilisateur_has_lerole.lutilisateur_idutilisateur =lutilisateur.idlutilisateur
            INNER JOIN lerole
            on lerole.idlerole= lutilisateur_has_lerole.lerole_idlerole LIMIT ?,?;
		";
        $sqlQuery = $this->db->prepare($sql);
        $sqlQuery->bindValue(1, $premsLIMIT, PDO::PARAM_INT);
        $sqlQuery->bindValue(2, $nbParPage, PDO::PARAM_INT);
        $sqlQuery->execute();

        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
 
 
 
    }



    public function selectLutilisateurCountById(): int
    {

        $sql = "SELECT COUNT(idlutilisateur) AS b
			  FROM lutilisateur";


        $sqlQuery = $this->db->query($sql);


        $recup = $sqlQuery->fetch(PDO::FETCH_ASSOC);
        return (int) $recup['b'];

    }

    public function SelectUserByRoleid( int $idutilisateur){
        $sql="SELECT l.idlutilisateur,l.lenomutilisateur,l.lenom,l.leprenom,l.lemail,l.actif,le.idlerole ,le.lintitule 
FROM lutilisateur l
LEFT JOIN lutilisateur_has_lerole lu ON lu.lutilisateur_idutilisateur=l.idlutilisateur
LEFT JOIN lerole le ON le.idlerole=lu.lerole_idlerole
WHERE l.idlutilisateur = :id

";
        $recup = $this->db->prepare($sql);
        $recup->bindParam("id", $idutilisateur, PDO::PARAM_INT);

        try {
            $recup->execute();

            // si pas de résultats
            if ($recup->rowCount() == 0) {
                return [];
            }

            return $recup->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return [];
        }
    }
    function updateUserandlore(lutilisateur $utilisateur, $idlore) {


        $this->db->beginTransaction();

        // update préparé de l'article
        $sql = "UPDATE lutilisateur 
            SET lenomutilisateur = :lenomutilisateura,
                lenom = :lenoma,
                leprenom = :leprenoma,
                lemail = :lemaila
            WHERE idlutilisateur = :idlutilisateura;";
        $update = $this->db->prepare($sql);
        $update->bindValue("idlutilisateura",$utilisateur->getIdlutilisateur(), PDO::PARAM_INT);
        $update->bindValue("lenomutilisateura", $utilisateur->getLenomutilisateur(), PDO::PARAM_STR);
        $update->bindValue("lenoma", $utilisateur->getLenom(), PDO::PARAM_STR);
        $update->bindValue("leprenoma", $utilisateur->getLeprenom(), PDO::PARAM_STR);
        $update->bindValue("lemaila", $utilisateur->getLemail(), PDO::PARAM_STR);

        $update->execute();




        $sql = "DELETE FROM lutilisateur_has_lerole WHERE lutilisateur_idutilisateur = ?";
        $delete = $this->db->prepare($sql);
        $delete->bindValue(1, $utilisateur->getIdlutilisateur(), PDO::PARAM_INT);

        $delete->execute();

        if (!empty($idlore)) {


            $sql = "INSERT INTO lutilisateur_has_lerole (lutilisateur_idutilisateur,lerole_idlerole) VALUES ";



                $id = (int) $idlore;

                $sql .= "(".$utilisateur->getIdlutilisateur().",$id)";




            $this->db->exec($sql);

        }

        try{

            $this->db->commit();
            return true;
        } catch (PDOException $ex) {

            $this->db->rollBack();
            echo '<h2 style="color: red;">ERROR: ' . $ex->getMessage() . '</h2>';
            return false;
        }
    }


    public function UserDelete(int $id):void
    {
        $sql = "DELETE FROM lutilisateur WHERE idlutilisateur=?";
        $req = $this->db->prepare($sql);
        $req->bindValue(1, $id, PDO::PARAM_INT);
        $req->execute();

    }
    public function getUniqueId (string  $email){
      $sql = "SELECT luniqueid FROM lutilisateur WHERE lemail = ? ";
      $getUniqueid = $this->db->prepare($sql);
      $getUniqueid->bindValue(1,$email, PDO::PARAM_STR);
        try {
            $getUniqueid->execute();

            // si pas de résultats
            if ($getUniqueid->rowCount() == 0) {
                return [];
            }

            return $getUniqueid->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return [];
        }
    }


    public function changePassword(lutilisateur $user){

        $user->setLemotdepasseCrypte($user->getLemotdepasse());

        $sql = "UPDATE lutilisateur SET lemotdepasse = :lemotdepasse WHERE luniqueid = :luniqueid";
        $update = $this->db->prepare($sql);
        $update->bindValue("lemotdepasse",$user->getLemotdepasse(),PDO::PARAM_STR);
        $update->bindValue("luniqueid",$user->getLuniqueid(),PDO::PARAM_STR);

        try {
            $update->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }


    public function checkPasswordInDb($idPassword){
        $sql = "SELECT lemotdepasse FROM lutilisateur WHERE idlutilisateur= ?";
        $selectPasswordById = $this->db->prepare($sql);
        $selectPasswordById->bindValue(1,$idPassword,PDO::PARAM_INT);

        try {
            $selectPasswordById->execute();

            // si pas de résultats
            if ($selectPasswordById->rowCount() == 0) {
                return [];
            }

            return $selectPasswordById->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return [];
        }

    }
    }


