<?php

/*
 * Classe permettant d'afficher la pagination suivant le nombre d'éléments par page
 */
class pagination
{
    public static function pagine(int $nbTotalElement, int $nbParPage, int $pageActu, string $variableGET): string {

        // variable qui va renvoyer le code HTML de la pagination
        $sortie="";

        // on va calculer le nombre de pages nécessaires en divisant le nombre total d'éléments par le nombre par page, le tout arrondit à l'entier supérieur (ceil)
        $nbPages = ceil($nbTotalElement/$nbParPage);

        // si une seule page
        if($nbPages==1){
            // pas de pagination si non nécessaire

        }else{
            $sortie.="<div>";

            // tant qu'on a des pages
            for($i=1;$i<=$nbPages;$i++){

                // si on est au premier tour de boucle (accueil) << <
                if($i==1) {
                    // si on est sur la page 1, pas de liens, sinon retour à l'accueil et page précédente
                   $sortie.= ($pageActu==1)
                           ?  "<a href='?$variableGET=1'><<</a> <a href='?$variableGET=".($pageActu-1)."'><</a> "
                           :"<a href='?$variableGET=1'><<</a> <a href='?$variableGET=".($pageActu-1)."'><</a> ";

                }

                // si on est sur une page, le lien n'est pas cliquable
                $sortie .= ($i==$pageActu)
                    ? "$i "
                    : "<a href='?$variableGET=$i'>$i</a> ";

                // si on est au dernier tour de la boucle
                if($i==$nbPages){
                    $sortie .=
                        ($i==$pageActu)
                            ? "> >>"
                            : " <a href='?$variableGET=".($pageActu+1)."'>></a> <a href='?$variableGET=$nbPages'>>></a>"
                    ;
                }

            }
            $sortie.="</div>";
        }
        return $sortie;
    }
}