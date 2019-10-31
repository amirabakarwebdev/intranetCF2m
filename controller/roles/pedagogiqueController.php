<?php


if (!empty($_GET)) {
    require_once "../controller/modules/gestionLafiliere.php";
	
	require_once "../controller/modules/gestionLasession.php";


    if (isset($_GET['viewdetailsession'])) {


        $selectEval = $evaluationM->selectAllFiliereForEval();


        echo $twig->render("view_stagiaires/choix_filiere.html.twig", ['lutilisateur' => $selectEval]);


//choix des stagiaires de la filiere choisie
    } elseif (isset($_GET['choixFiliere']) && ctype_digit($_GET['choixFiliere'])) {
        $choixFiliere = (int)$_GET['choixFiliere'];

        $nomStagiaire = $evaluationM->selectAllStagiairesForEval($choixFiliere);

        echo $twig->render("view_stagiaires/choix_stagiaire.html.twig", ['lutilisateur' => $nomStagiaire]);


// choix du stagiaire pour voir son profil
    } elseif (isset($_GET['profil']) && ctype_digit($_GET['profil'])) {

        $profil = (int)$_GET['profil'];


        $profilStagiaire = $evaluationM->selectProfilStagiaire($profil);

        echo $twig->render('view_stagiaires/profil_stagiaire.html.twig', ['idlutilisateur' => $profilStagiaire]);


//update des infos du stagiaire
    } elseif (isset($_GET['modifInfo']) && ctype_digit($_GET['modifInfo'])) {

        if (isset($_POST['idlutilisateur'])) {

            $updateStagiaire = new evaluation($_POST);


            $evaluationM->updateStagiaire($updateStagiaire, $_GET['modifInfo']);

            header("Location: ./?modifInfo");
            var_dump($_POST);
        } else {

            echo $twig->render('view_stagiaires/modifier_stagiaire.html.twig', ['section' => $evaluationM->selectProfilStagiaire($_GET['modifInfo'])]);
        }


//recherche d'un stagiaire avec un moteur de recherche
    }elseif(isset($_GET['viewprofil'])){


             $stagiaire = rechercheStagiaire::searchStagiaire($_POST);

            echo $twig->render('view_stagiaires/recherche_stagiaire.html.twig',['user'=>$stagiaire]);
            
        }




    



    } else {


        if (!isset($_SESSION['bandeau'])) {
            $pourEntree = true;
            $_SESSION['bandeau'] = true;
        } else {
            $pourEntree = false;
        }

        echo $twig->render('roles/pedagogique/pedagogique_homepage.html.twig', ['entree' => $pourEntree, "session" => $_SESSION]);
    }
