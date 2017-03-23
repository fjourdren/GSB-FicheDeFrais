<?php
@session_start();

require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if($_SESSION['login'] == "") { 	//on vérifie que le visiteur est bien connecté
	header('location: connexion.php');
	exit;
}


//récup des infos
$id         = secureVariable($_GET['id']);
$idVisiteur = secureVariable($_SESSION['idVisiteur']);


//récupération de la fiche de frais
$sql        = "SELECT * FROM fichefrais 
				WHERE id='$id'";
$fichefrais = tableSQL($sql)[0];


//vérification de l'identité de l'éditeur
if($idVisiteur != $fichefrais['idVisiteur']) {
	addFlash('Erreur', 'vous n\'&#234;tes pas le propri&#233;taire de cette fiche de frais');
	header('location: visiteur-listeFicheFrais.php');
	exit;
}


//vérification de l'état de la fiche de frais
if($fichefrais['idEtat'] != "CR") {
	addFlash('Erreur', 'La fiche de frais est d&#233;jà clotur&#233;');
	header('location: visiteur-listeFicheFrais.php');
	exit;
}



$sql = "DELETE FROM fichefrais 
		WHERE id='$id'";
executeSQL($sql);



addFlash('Succ&egrave;s', 'Fiche de frais supprim&#233;');
header('location: visiteur-listeFicheFrais.php');
exit;