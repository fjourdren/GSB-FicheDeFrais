<?php
@session_start();

require_once 'include/config.inc.php'; //appelle fichier init pour les variable constante à tout le site
require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if($_SESSION['login'] != COMPTANAME) { 	//on vérifie que l'utilisateur a le droit d'être sur cette page
	header('location: connexion.php');
	exit;
}




if(($_POST['id'] == null) 
	|| ($_POST['libelle'] == null) 
	|| ($_POST['montant'] == null)) {

	addFlash('Erreur', 'Merci de remplir les champs obligatoires.');
	header('location: comptable-ajouterForfaitForm.php');
	exit;

}


$id       = secureVariable($_POST['id']);
$libelle  = secureVariable($_POST['libelle']);
$montant  = secureVariable($_POST['montant']);



//vérification que le montant est positif et valide
if((!is_numeric($montant))
	|| ($montant < 0)) {
		//mise en session du message flash et redirect
		addFlash('Erreur', 'Le montant doit &#234;tre positif et doit &#234;tre num&#233;rique.');
		header('location: comptable-ajouterForfaitForm.php');
		exit;
}


//vérification unicité de l'ID
$sql = "SELECT id FROM Forfait WHERE id='$id' LIMIT 1";

if(compteSQL($sql) == 1) {
	//mise en session du message flash et redirection
	addFlash('Erreur', 'Un forfait poss&#233;de d&#233;j&#224; cet ID.');
	header('location: comptable-ajouterForfaitForm.php');
	exit;
}



$sql = "INSERT INTO Forfait(id, libelle, montant) 
		VALUES('$id', '$libelle', '$montant')";

$resultat = executeSQL($sql);



addFlash('Succ&#232;s', 'Forfait ajout&#233;.');
header('location: comptable-listeForfait.php');
exit;