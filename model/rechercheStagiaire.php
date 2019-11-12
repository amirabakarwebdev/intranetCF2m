<?php




class rechercheStagiaire{

    private $db;

    public function __construct(PDO $connect)
    {
        $this->db = $connect;

    }

public  function researchStagiaire(string $recuperation){

    /*if (isset($_POST['param'])) {
        $recuperation = $_POST['param'];*/


        
    if (!empty($recuperation)) {
        // variable contenant la requête MySQL
        $sql1 = "SELECT u.idlutilisateur, u.lenom, u.leprenom, s.lacronyme
        FROM lutilisateur u
        LEFT JOIN linscription i
        ON i.utilisateur_idutilisateur = u.idlutilisateur
        LEFT JOIN lasession s
        ON s.idlasession = i.lasession_idsession
        WHERE (u.lenom  
        LIKE :in) 
        OR (u.leprenom
        LIKE :in)
        ORDER BY s.lacronyme;";
         

        // exécution de la requête
        $recup = $this->db->prepare($sql1);
        $recup->bindValue("in","%".$recuperation."%",PDO::PARAM_STR);
        $recup->execute();

                if ($recup->rowCount() == 0) {
                    return json_encode("");
                }
    

        
                while ($row = $recup->fetch(PDO::FETCH_ASSOC)){
                    $resultset[] = $row['idlutilisateur']." | ".$row["leprenom"]." ".$row["lenom"]." ".$row["lacronyme"];
                
                }
                if (!empty($resultset)) {
                    $chaineretour = json_encode($resultset);
                } else {
                    $chaineretour = json_encode("");
                }
                return $chaineretour;

        
                
            }
         }






    }


