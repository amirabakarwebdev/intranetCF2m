<?php

// load lutilisateur manager
$lutilisateurM=new lutilisateurManager($db_connect);
// load lutilisateur inscription
$linscriptionM=new linscriptionManager($db_connect);
// load lutilisateur role
$leroleM=new leroleManager($db_connect);
// load lutilisateur droit
$ledroitM=new ledroitManager($db_connect);
// load lutilisateur session
$lasessionM=new lasessionManager($db_connect);
// load lutilisateur filiere
$lafiliereM=new lafiliereManager($db_connect);
// load lutilisateur conge
$lecongeM=new lecongeManager($db_connect);
// load eval stagiaire
$evaluationM= new evaluationManager($db_connect);

// Ajoute une variable global twig contenant le nom et le prénom de l'utilisateur actuel
$twig->addGlobal('currentUser', $_SESSION['leprenom'] . ' ' . $_SESSION['lenom']);

// deconnection
if(isset($_GET['deconnect'])){
    $lutilisateurM->disconnectLutilisateur();
}

// switch suivant l'id des rôles (pour le moment, un rôle, un controleur)
switch ($_SESSION['idlerole']) {
    case "1":
        include "roles/personnelController.php";
        break;
    case "2":
        include "roles/accueilController.php";
        break;
    case "3":
        include "roles/pedagogiqueController.php";
        break;
    case "4":
        include "roles/superAdminController.php";
        break;
    case "5":
        include "roles/stagiaireController.php";
        break;
    default:
        echo "Session introuvable";
}
