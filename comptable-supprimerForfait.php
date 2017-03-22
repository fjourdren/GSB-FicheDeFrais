<?php
@session_start();

require_once 'include/config.inc.php';
require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if($_SESSION['login'] != COMPTANAME) { 	//on vérifie que l'utilisateur a le droit d'être sur cette page
	header('location: connexion.php');
	exit;
}


if($_GET['id'] != "") {
	
	$id = secureVariable($_GET['id']);

	//requete sql qui supprime le forfait
	$sql = "DELETE FROM forfait 
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


header('location: comptable-listeForfait.php');
exit;