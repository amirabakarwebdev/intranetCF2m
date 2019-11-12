<?php
session_start();

require_once '../config.php';

spl_autoload_register(function ($class) {
    include '../model/' . $class . '.php';
});

try {
    $db_connect = new MyPDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . DB_PORT . ';charset=' . DB_CHARSET,
            DB_LOGIN,
            DB_PWD,
            null,
            PRODUCT);
} catch (PDOException $e) {
    echo 'Message d\'erreur : ' . $e->getMessage();
    echo '<br>';
    echo 'Code d\'erreur : ' . $e->getCode();
}
/*
 * Pas connecté, donc on veut afficher le contrôleur public
 */
if (!isset($_SESSION['TheIdSess']) || $_SESSION['TheIdSess'] != session_id()) {
    return false;
} 

if(isset($_POST['param'])){

$research = new rechercheStagiaire($db_connect);
$result = $research->researchStagiaire($_POST['param']);
echo $result;
}
//var_dump($_POST);