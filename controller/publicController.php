<?php
$utilisateurManager = new lutilisateurManager($db_connect);

if (isset($_POST['lenomutilisateur']) && isset($_POST['lemotdepasse'])) {
    $utilisateur = new lutilisateur($_POST);
    if ($utilisateurManager->connectLutilisateur($utilisateur)) {
        header('Location: ./');
    } else {
        echo $twig->render("public/homepage.html.twig", ["error_connection" => true]);
    }
} else if(isset($_GET['motdepasseoublier'])){
    $lutilisateurM = new lutilisateurManager($db_connect);
    $user = new lutilisateur($_POST);

    echo $twig->render("public/modepasseoublier.html.twig", ["motdepasse" => $lutilisateurM->motDePasseOublier($user)]);
}else if(isset ($_GET['mail'])&& !empty($_GET['mail'])&& $utilisateurManager->checkMail(urldecode($_GET['mail']))){

    $uniqueid = $utilisateurManager->getUniqueId($_GET['mail'])['luniqueid'];

    echo urldecode($_GET['mail']);
    ini_set('SMTP', 'relay.skynet.be');
    ini_set('sendmail_from', 'DD@gmail.com'); // Nécessaire pour passer les vérifications du SMTP
    $to = urldecode($_GET['mail']) ; // Récipients, en cas de multiples personnes chacun pourra voir les autres destinataires
    $from = 'supportCF2M@gmail.com'; // Sous quel adresse email le récipient recevra le mail
    $fromName = 'supportCF2M'; // Quel nom le récipient verra dans sa boîte mail
    $subject = "TEST"; // Titre du mail
    $message = "Please Click on the link here: <a href='localhost/intranetCF2m/public/?checkUniqueID&key=$uniqueid' title='My page'>Cliquer sur le lien pour changer votre mot de passe</a>"; // Message du mail
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'From: ' . $fromName . '<' . $from . '>' . "\r\n";
//$headers .= 'Cc: ' . "\r\n"; // Carbon copy, ne peut pas être vide (Erreur "partial domain" ?)
    $headers .= 'Bcc: keloorap@gmail.com' . "\r\n"; // Blind carbon copy, à utiliser en cas de multiples récipients pour préserver l'anonymat entre eux
    $mail = mail($to, $subject, $message, $headers);
    if($mail) {
        echo "Email envoyé avec succès";
    } else {
        echo "Echec de l'envoi du mail";
    }
    echo $twig->render("public/homepage.html.twig");

}else if( isset($_GET['checkUniqueID']) && isset($_GET['key']) ) {

    //$recupIdPasswod = $utilisateurManager->checkPasswordInDb($_GET['key']);

    echo $twig->render("public/newpassword.html.twig", ['key' => $_GET['key']]);

    /*if( password_verify('1234', $recupIdPasswod['lemotdepasse']) ) {

        $user =  new lutilisateur( $recupIdPasswod );

        $recup = $utilisateurManager->changePassword($passwordUpdateID);
        echo "Le mot de passe correspond ";

    } else {

        echo "Le mot de passe ne correspond pas";

    }*/

} elseif(isset($_POST['lemotdepasse']) && isset($_POST['luniqueid'])) {

    $user = new lutilisateur($_POST);
    $utilisateurManager->changePassword($user);
    header("Location: ./");

}
 else {
  if(isset ($_GET['mail'])&& !empty($_GET['mail'])&& !$utilisateurManager->checkMail(urldecode($_GET['mail']))){
    echo '<div id="fade" class="alert-false"><span class="closebtn" onclick="this.partelement.style.display="none";">&times;</span>Ouups ! Ce mail n\'existe pas !</div>';
  }

    echo $twig->render("public/homepage.html.twig");
}