<?php
@session_start();

require_once 'include/config.inc.php'; //appelle fichier init pour les bariable constante à tout le site
require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if($_SESSION['login'] != COMPTANAME) { 	//on vérifie que l'utilisateur a le droit d'être sur cette page
	header('location: connexion.php');
	exit;
}



// si le champ invisible de l'id est nulle, alors on renvoie une erreur et on redirige
if($_POST['id'] == null) {
	addFlash('Erreur', 'Erreur ID invisible');
	header('location: comptable-listeForfait.php');
	exit;
}


//on vérifie que les champs obligatoires sont présents.
if(($_POST['libelle'] == null)
	|| ($_POST['montant'] == null)) {

	addFlash('Erreur', 'Merci de remplir les champs obligatoires');
	header('location: comptable-modifierForfaitForm.php?id='.$_POST['idAvant']);
	exit;

}



$id  = secureVariable($_POST['id']);
$libelle  = secureVariable($_POST['libelle']);
$montant  = secureVariable($_POST['montant']);
//vérification que le montant est positif et valide
if((!is_numeric($montant))
	|| ($montant < 0)) {
		//mise en session du message flash et redirect
		addFlash('Erreur', 'Le montant doit &#234;tre positif et doit &#234;tre num&#233;rique');
		header('location: comptable-modifierForfaitForm.php?id='.$_POST['idAvant']);
		exit;
}


$sql = "UPDATE forfait
		SET libelle='$libelle', montant='$montant'  
		WHERE id='$id'";

$resultat = executeSQL($sql);


//mise en session du message flash puis redirection
addFlash('Succ&#232;s', 'Forfait modifi&#233;');
header('location: comptable-listeForfait.php');
exit;