<?php
@session_start();

require_once 'include/config.inc.php';
require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if(($_SESSION['login'] != ADMINNAME) && ($_SESSION['login'] != COMPTANAME)) { 	//on vérifie l'utilisateur
	header('location: connexion.php');
	exit;
}


if($_GET['id'] != "") {
	
	$id = secureVariable($_GET['id']);

	//requete sql qui rÃƒÂ©cupÃƒÂ¨re tous les visiteurs
	$sql = "DELETE FROM Visiteur 
			WHERE id='$id'";
	$resultat = executeSQL($sql);
	
	if($resultat) {
		addFlash('Succ&#232;s', 'Supression r&#233;ussi.');
	} else {
		addFlash('Erreur', 'Echec de la supression.');
	}

} else {
	addFlash('Erreur', 'Aucun ID renseign&#233;.');
}


header('location: admin-listeVisiteur.php');
exit;