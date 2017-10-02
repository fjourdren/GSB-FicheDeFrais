<?php
@session_start();

require_once 'include/config.inc.php'; //appelle fichier init pour les bariable constante Ã  tout le site
require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if($_SESSION['login'] != COMPTANAME) { 	//on vÃ©rifie que l'utilisateur a le droit d'Ãªtre sur cette page
	header('location: connexion.php');
	exit;
}



// si le champ invisible de l'id est nulle, alors on renvoie une erreur et on redirige
if($_POST['id'] == null) {
	addFlash('Erreur', 'Erreur ID invisible');
	header('location: comptable-listeForfait.php');
	exit;
}


//on vÃ©rifie que les champs obligatoires sont prÃ©sents.
if(($_POST['libelle'] == null)
	|| ($_POST['montant'] == null)) {

	addFlash('Erreur', 'Merci de remplir les champs obligatoires');
	header('location: comptable-modifierForfaitForm.php?id='.$_POST['idAvant']);
	exit;

}



$id       = secureVariable($_POST['id']);
$libelle  = secureVariable($_POST['libelle']);
$montant  = secureVariable($_POST['montant']);
//vÃ©rification que le montant est positif et valide
if((!is_numeric($montant))
	|| ($montant < 0)) {
		//mise en session du message flash et redirect
		addFlash('Erreur', 'Le montant doit &#234;tre positif et doit &#234;tre num&#233;rique');
		header('location: comptable-modifierForfaitForm.php?id='.$_POST['idAvant']);
		exit;
}



//Select du vieux montant du forfait pour mettre à jour les fiches de frais
$sql = "SELECT montant FROM forfait WHERE id='$id'";
$oldMontant = champSQL($sql);


$sql = "UPDATE forfait
		SET libelle='$libelle', montant='$montant'  
		WHERE id='$id'";

$resultat = executeSQL($sql);



//mise à jour du montant des fiches de frais qui contiennent le forfait
$sql = "SELECT idFicheFrais, quantite FROM LigneFraisForfait WHERE idForfait='$id'"; //recherche des fiche dont le montant total sera à modifier
$fichesAModifier = tableSQL($sql);

foreach ($fichesAModifier as $fiche) {
	$montantTotal    = $oldMontant * $fiche['quantite']; //calcul montant à supprimer
	$newValueForfait = $montant * $fiche['quantite'];
	$idFiche         = $fiche['idFicheFrais'];

	$sql = "UPDATE FicheFrais SET montantValide=(montantValide-'$montantTotal') + '$newValueForfait' WHERE id='$idFiche'"; //recherche des fiche dont le montant total sera à modifier
	executeSQL($sql);
}





//mise en session du message flash puis redirection
addFlash('Succ&#232;s', 'Forfait modifi&#233;');
header('location: comptable-listeForfait.php');
exit;
