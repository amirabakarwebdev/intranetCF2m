<?php

//require_once "jquery-3.3.1.js";

//require_once "jquery-ui";

class rechercheStagiaire{


    public static function searchStagiaire($data) {

        if (isset($_POST['param'])) {
            $demande = $_POST['param'];
        
            if (!empty($demande)) {
                // variable contenant la requête MySQL
                $sql1 = 'SELECT u.lenom, u.leprenom, s.lacronyme
                FROM lutilisateur u
                INNER JOIN linscription i
                ON i.utilisateur_idutilisateur = u.idlutilisateur
                INNER JOIN lasession s
                ON s.idlasession = i.lasession_idsession
                WHERE (u.lenom  
                LIKE "%'.$demande.'%") 
                OR (u.leprenom
                LIKE "%'.$demande.'%")
                ORDER BY s.lacronyme';
        
                // exécution de la requête
                $recup = $sql1->prepare();
                $recup->bindValue(1,$data,PDO::PARAM_INT);
                $recup->execute();
        
        
                        if ($recup->rowCount() === 0) {
                            return [];
                        }
                        return $recup->fetchAll(PDO::FETCH_ASSOC);
        
                /*while ($row = mysqli_fetch_assoc($recup)){
                    $resultset[] = $row["leprenom"]." ".$row["lenom"]." ".$row["lacronyme"];
                
                
                if (!empty($resultset)) {
                    $chaineretour = json_encode($resultset);
                } else {
                    $chaineretour = json_encode("");
                }
        
                
            }*/
         }


      }



    }

}