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
$mois            = secureVariable($_POST["mois"]);
$annee           = secureVariable($_POST["annee"]);

$nbJustificatifs = secureVariable($_POST['nbJustificatifs']);

$idVisiteur      = secureVariable($_SESSION['idVisiteur']);


//vérification date
if(!is_numeric($mois)
		|| !is_numeric($annee)
		|| ($mois < 0)
		|| ($annee < 0)) {
			addFlash('Erreur', 'Date invalide');
			header('location: visiteur-listeFicheFrais.php');
			exit;
}
		


//vérifie si la date n'est pas dans le futur
if(date('m') == $mois) {
	if(date('j') < NUMERO_JOUR_DE_CLOTURE) {
		$now            = new DateTime(date('Y').'-'.date('m')); //maintenant
		$ficheFraisMois = new DateTime($annee.'-'.$mois); //date limite de saisie
		$interval       = $now->diff($ficheFraisMois);
		//si la date est dans le futur ou si la cloture du mois est déjà faites, la date est invalide

		if(($interval->format('%R%') == "+") 
			&& ($interval->format('%R%a') != "+0")) {
			addFlash('Erreur', 'Cette date ci est invalide');
			header('location: visiteur-ajouterForm.php');
			exit;
		}
	} else {
		addFlash('Erreur', 'La cloture des fiches de frais a d&#233;j&#224; &#233;t&#233; effectu&#233;.');
		header('location: visiteur-ajouterForm.php');
		exit;
	}
}



//vérifie si une fiche de frais n'est pas déjà créer pour ce mois ci
$sql = "SELECT * FROM fichefrais 
		WHERE mois='$mois' 
		AND annee='$annee' 
		AND idVisiteur='$idVisiteur'
		LIMIT 1";
if(compteSQL($sql) != 0) {
	//mise en session du message flash
	addFlash('Erreur', 'Cette enregistrement existe d&#233;j&#224;, merci d\'aller dans le menu "Mes fiches de frais"');
	header('location: visiteur-ajouterForm.php');
	exit;
}

$sql = "INSERT INTO fichefrais(idVisiteur, mois, annee, dateModif, idEtat, nbJustificatifs)
		VALUES ('$idVisiteur', '$mois', '$annee', NOW(), 'CR', '$nbJustificatifs')";

executeSQL($sql);


$sql = "SELECT * FROM fichefrais 
		WHERE idVisiteur='$idVisiteur' 
		AND mois='$mois' 
		AND annee='$annee'
		LIMIT 1";
$ficheFrais = tableSQL($sql);


$idFicheFrais = secureVariable($ficheFrais[0]['id']);




//GESTION DES LIGNES
$sql                 = "SELECT id, montant FROM forfait";
$listeForfaits       = tableSQL($sql);
$montantFicheDeFrais = 0;

foreach ($listeForfaits as $key => $forfait) {

	$forfaitID           = secureVariable($forfait['id']);
	$valeurFormDuForfait = secureVariable($_POST[$forfait['id']]);


	if((isset($valeurFormDuForfait))
		&& ($valeurFormDuForfait != 0)) {

		if(($valeurFormDuForfait < 0)
			|| (!is_numeric($valeurFormDuForfait))) {
			addFlash('Erreur', 'Les valeurs doivent être des nombres positifs.');
			header('location: visiteur-listeFicheFrais.php');
		}

		$sql = "INSERT INTO lignefraisforfait(idFicheFrais, idForfait, quantite) 
				VALUES ('$idFicheFrais', '$forfaitID', '$valeurFormDuForfait')";
		executeSQL($sql);

		//calcul montant total de la fiche de frais
		$montantFicheDeFrais = $montantFicheDeFrais + $valeurFormDuForfait * $forfait['montant'];
	}

}


$maxHorsForfait = $_POST['horsForfaitNumber'];
for($i = 1; $i <= $maxHorsForfait; $i ++) {
	
	$libelleHorsForfait= $_POST["horsForfait".$i."Libelle"];
	$montantHorsForfait = $_POST["horsForfait".$i."Montant"];
	
	if(($montantHorsForfait< 0)
			|| (!is_numeric($montantHorsForfait))) {
				addFlash('Erreur', 'Les valeurs de hors forfait doivent &#234;tre des nombres positifs.');
				header('location: visiteur-ajouterForm.php');
			}
			
			//insert
			
			$montantFicheDeFrais+= $montantHorsForfait;
}



//on enregistre le montant de la fiche de frais
$sql = "UPDATE fichefrais SET montantValide='$montantFicheDeFrais' 
		WHERE id='$idFicheFrais'";
executeSQL($sql);


//mise en session du message flash
addFlash('Succ&#232;s', 'Fiche de frais cr&#233;&#233;e');
header('location: visiteur-ajouterForm.php');
exit;

?>