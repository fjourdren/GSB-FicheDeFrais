<?php
@session_start();

require_once 'include/config.inc.php'; //appelle fichier init pour les bariable constante à tout le site
require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if($_SESSION['login'] == "") { 	//on vérifie que le visiteur est bien connecté
	header('location: visiteur-ajouterForm.php');
	exit;
}




//GESTION DE LA FICHE
$nbJustificatifs = secureVariable($_POST['nbJustificatifs']);
$idVisiteur      = secureVariable($_SESSION['idVisiteur']);




//calcul du mois en cours
$day   = date("j");
$month = date("n");
$year  = date("Y");

if($day >= NUMERO_JOUR_DE_CLOTURE) {
	$month++;

	if($month >= 12) {
		$month -= 12;
		$year++;
	}
}


//vérifie si une fiche de frais n'est pas déjà créer pour ce mois ci
$sql = "SELECT * FROM FicheFrais 
		WHERE mois='$month' 
		AND annee='$year' 
		AND idVisiteur='$idVisiteur'
		LIMIT 1";
if(compteSQL($sql) != 0) {
	//mise en session du message flash
	addFlash('Erreur', 'Cette enregistrement existe d&#233;j&#224;, merci d\'aller dans le menu "Mes fiches de frais"');
	header('location: visiteur-ajouterForm.php');
	exit;
}

$sql = "INSERT INTO FicheFrais(idVisiteur, mois, annee, dateModif, idEtat, nbJustificatifs)
		VALUES ('$idVisiteur', '$month', '$year', NOW(), 'CR', '$nbJustificatifs')";

executeSQL($sql);


$sql = "SELECT * FROM FicheFrais 
		WHERE idVisiteur='$idVisiteur' 
		AND mois='$month' 
		AND annee='$year'
		LIMIT 1";
$ficheFrais = tableSQL($sql);


$idFicheFrais = secureVariable($ficheFrais[0]['id']);


addFlash('Succ&#232;s', 'Fiche de frais cr&#233;&#233;e');


//GESTION DES LIGNES
$sql                 = "SELECT id, montant FROM Forfait";
$listeForfaits       = tableSQL($sql);
$montantFicheDeFrais = 0;

foreach ($listeForfaits as $key => $forfait) {

	$forfaitID           = secureVariable($forfait['id']);
	$valeurFormDuForfait = secureVariable($_POST[$forfait['id']]);


	if((isset($valeurFormDuForfait))
		&& ($valeurFormDuForfait != 0)) {

		if(($valeurFormDuForfait < 0)
			|| (!is_numeric($valeurFormDuForfait))) {
			addFlash('Erreur', 'Les valeurs doivent être des nombres positifs');
			header('location: visiteur-listeFicheFrais.php');
		}

		$sql = "INSERT INTO LigneFraisForfait(idFicheFrais, idForfait, quantite) 
				VALUES ('$idFicheFrais', '$forfaitID', '$valeurFormDuForfait')";
		executeSQL($sql);

		//calcul montant total de la fiche de frais
		$montantFicheDeFrais = $montantFicheDeFrais + $valeurFormDuForfait * $forfait['montant'];
	}

}


$sqlConcat = "INSERT INTO LigneFraisHorsForfait(idFicheFrais, dteFraisHF, libFraisHF, quantite, montant) VALUES ";

$maxHorsForfait = secureVariable($_POST['horsForfaitNumber']);
for($i = 0; $i < $maxHorsForfait; $i++) {
	
	$libelleHorsForfait  = secureVariable($_POST["horsForfait".$i."Libelle"]);
	$dateHorsForfait     = secureVariable($_POST["horsForfait".$i."Date"]);
	$quantiteHorsForfait = secureVariable($_POST["horsForfait".$i."Quantite"]);
	$montantHorsForfait  = secureVariable($_POST["horsForfait".$i."Montant"]);
	
	//preparation de la variable date pour l'insert, si c'est null on met à NOW
	if(!$dateHorsForfait) {
		$dateHorsForfait = "NOW()";
	} else {
		$dateHorsForfait = "'".$dateHorsForfait."'";
	}

	//si il n'y a pas de libellé, on met une valeur par défaut
	if(!$libelleHorsForfait) {
		$libelleHorsForfait = "Sans nom";
	}

	if($montantHorsForfait < 0
		|| !is_numeric($montantHorsForfait)
		|| $quantiteHorsForfait < 0
		|| !is_numeric($quantiteHorsForfait)) {
		addFlash('Erreur', 'Certaines valeurs de hors forfaits ne sont pas valides');
	} else {
		//insert
		if($i != $maxHorsForfait - 1) {
			$sqlConcat .= "('$idFicheFrais', $dateHorsForfait, '$libelleHorsForfait', '$quantiteHorsForfait', '$montantHorsForfait'), ";
		} else {
			$sqlConcat .= "('$idFicheFrais', $dateHorsForfait, '$libelleHorsForfait', '$quantiteHorsForfait', '$montantHorsForfait');";
		}
				
		$montantFicheDeFrais += $montantHorsForfait * $quantiteHorsForfait;
	}
}

executeSQL($sqlConcat);



//on enregistre le montant de la fiche de frais
$sql = "UPDATE FicheFrais SET montantValide='$montantFicheDeFrais' 
		WHERE id='$idFicheFrais'";
executeSQL($sql);


//mise en session du message flash
header('location: visiteur-listeFicheFrais.php');
exit;

?>