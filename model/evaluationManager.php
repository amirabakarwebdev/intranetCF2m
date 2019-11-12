<?php

class evaluationManager {
    private $db;
    public function __construct(MyPDO $connect) {
        $this->db = $connect;
    }


    //selection de tous les stagiaires

    public function selectAllStagiairesForEval(int $idlafiliere): array{

        $sql = "SELECT u.lenom, u.leprenom, u.idlutilisateur
		FROM lutilisateur u
		INNER JOIN linscription i
		ON i.utilisateur_idutilisateur = u.idlutilisateur
		INNER JOIN lasession s
        ON s.idlasession = i.lasession_idsession
        INNER JOIN lafiliere f
        ON f.idlafiliere = s.lafiliere_idfiliere
        where f.idlafiliere = ?";
        
        $recup = $this->db->prepare($sql);
        $recup->bindValue(1,$idlafiliere,PDO::PARAM_INT);
        $recup->execute();
        if ($recup->rowCount() === 0) {
            return [];
        }
        return $recup->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAllFiliereForEval(): array{

        $sql="SELECT lenom, idlafiliere
              FROM lafiliere";

        $recup = $this->db->query($sql);
        if ($recup->rowCount() === 0) {
            return [];
        }
        return $recup->fetchAll(PDO::FETCH_ASSOC);  

    }

     //selection du stagiaire By id
    public function selectProfilStagiaire(int $idlutilisateur): array{

        $sql="SELECT (u.lenom) AS nomFamille, u.leprenom, u.lemail, (f.lenom) AS filiereName, (s.lenom) AS sessionName, u.lenomutilisateur, u.idlutilisateur, r.lintitule, l.debut, l.fin
        FROM lutilisateur u
        INNER JOIN lutilisateur_has_lerole h
        ON h.lutilisateur_idutilisateur = u.idlutilisateur
        INNER JOIN lerole r 
        ON r.idlerole = h.lerole_idlerole
        LEFT JOIN lafiliere f 
        ON f.idlafiliere = r.idlerole
        LEFT JOIN lasession s 
        ON s.idlasession = u.idlutilisateur
        LEFT JOIN linscription l 
        ON l.utilisateur_idutilisateur = u.idlutilisateur
        WHERE u.idlutilisateur = ?;";

        $recup = $this->db->prepare($sql);
        $recup->bindValue(1,$idlutilisateur,PDO::PARAM_INT);
        $recup->execute();


                if ($recup->rowCount() === 0) {
                    return [];
                }
                return $recup->fetch(PDO::FETCH_ASSOC);  


    }

    //update d'un stagiaire
    public function updateStagiaire(evaluation $update) {
        if ( empty($update->getLenom()) || empty($update->getLeprenom()) || empty($update->getLemail()) || empty($update->getLenom())
             || empty($update->getLenomutilisateur()) ||  empty($update->getIdlutilisateur()) ) {
            return false;
    	}

         $sql ="UPDATE lutilisateur, lerole, lafiliere, lasession  
         SET lutilisateur.Lenom=?, Leprenom=?, Lemail=?, lafiliere.lenom=?, Lenomutilisateur=?
         WHERE idlutilisateur=?";


        $updateLeStagiaire = $this->db->prepare($sql);
        $updateLeStagiaire->bindValue(1, $update->getLenom(), PDO::PARAM_STR);
        $updateLeStagiaire->bindValue(2, $update->getLeprenom(), PDO::PARAM_STR);
        $updateLeStagiaire->bindValue(3, $update->getLemail(), PDO::PARAM_STR);
        $updateLeStagiaire->bindValue(4, $update->getLenom(), PDO::PARAM_STR);
        $updateLeStagiaire->bindValue(5, $update->getLenomutilisateur(), PDO::PARAM_STR);
        $updateLeStagiaire->bindValue(6, $update->getIdlutilisateur(), PDO::PARAM_INT);


        try{

            $updateLeStagiaire->execute();
            return true;

        } catch(PDOException $e){

            echo '<h2 style="color: red;">ERROR: ' . $e->getMessage() . '</h2>';
            return false;

        }
    }
    

    /*public function searchStagiaire(evaluation $search){

        
            if (!empty($search)) {
                // variable contenant la requête MySQL
                $sql1 = 'SELECT u.lenom, u.leprenom, s.lacronyme
                FROM lutilisateur u
                INNER JOIN linscription i
                ON i.utilisateur_idutilisateur = u.idlutilisateur
                INNER JOIN lasession s
                ON s.idlasession = i.lasession_idsession
                WHERE (u.lenom  
                LIKE "%?%") 
                OR (u.leprenom
                LIKE "%?%")
                ORDER BY s.lacronyme';
                 
        
                // exécution de la requête
                $recup = $this->db->prepare($sql1);
                $recup->bindValue(1,$search,PDO::PARAM_STR);
                $recup->bindValue(2,$search,PDO::PARAM_STR);
                $recup->execute();
        
        
                        if ($recup->rowCount() === 0) {
                            return [];
                        }
                        return $recup->fetchAll(PDO::FETCH_ASSOC);
            }

            
    }*/








}