<?php
@session_start();

require_once 'include/config.inc.php';
require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if($_SESSION['login'] != COMPTANAME) { 	//on vÃ©rifie que l'utilisateur a le droit d'Ãªtre sur cette page
	header('location: connexion.php');
	exit;
}


if($_GET['id'] != "") {
	
	$id = secureVariable($_GET['id']);
	
	
	//mise à jour du montant des fiches de frais qui contiennent le forfait
	$sql = "SELECT montant FROM Forfait WHERE id='$id'"; //recherche du montant du forfait à modifier
	$montantVal = champSQL($sql);

	$sql = "SELECT idFicheFrais, quantite FROM LigneFraisForfait WHERE idForfait='$id'"; //recherche des fiche dont le montant total sera à modifier
	$fichesAModifier = tableSQL($sql);
	
	foreach ($fichesAModifier as $fiche) {
		$montantTotal = $montantVal * $fiche['quantite']; //calcul montant à supprimer
		$idFiche = $fiche['idFicheFrais'];
		
		$sql = "UPDATE FicheFrais SET montantValide = montantValide - '$montantTotal' WHERE id='$idFiche'"; //recherche des fiche dont le montant total sera à modifier
		executeSQL($sql);
	}
	
	
	
	//requete sql qui supprime le forfait
	$sql = "DELETE FROM forfait
	WHERE id='$id'";
	$resultat = executeSQL($sql);
	
	
	if($resultat) {
		addFlash('Succ&#232;s', 'Supression r&#233;ussi');
	} else {
		addFlash('Erreur', 'Echec de la supression');
	}

} else {
	addFlash('Erreur', 'Aucun ID renseign&#233;');
}


header('location: comptable-listeForfait.php');
exit;