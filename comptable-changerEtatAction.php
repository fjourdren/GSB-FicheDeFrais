<?php
@session_start();

require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/config.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if($_SESSION['login'] != COMPTANAME) { 	//on vérifie que le visiteur est bien connecté et a le droit
	header('location: connexion.php');
	exit;
}



//vérification de l'existance des paamètres nécessaires
if(($_GET['idFicheFrais'] == "") 
	&& ($_GET['etat']=="")) {
	addflash('Erreur', 'Param&#232;tres invalides');
	header('location: comptable-rechercheFicheDeFrais.php');
	exit;
}

$idFicheFrais = secureVariable($_GET['idFicheFrais']);
$etat         = secureVariable($_GET['etat']);



//on récupère la fiche de frais
$sql = "SELECT * FROM fichefrais WHERE id='$idFicheFrais'";
$fichefrais = tableSQL($sql)[0];




//gestions des différentes possibilités d'etat
switch($fichefrais['idEtat']) {
	case "CR":
			addflash('Erreur', 'Cette fiche de frais n\'est pas encore clotur&#233;.');
			header('location: comptable-rechercheFicheDeFrais.php?visiteurID='.secureDataAAfficher($fichefrais['idVisiteur']).'&mois='.secureDataAAfficher($fichefrais['mois']).'&annee='.secureDataAAfficher($fichefrais['annee']));
			exit;
		break;

	case "CL":
			if($etat == "VA") {
				//update dans la base de donnée
				$sql = "UPDATE fichefrais SET idEtat='$etat' WHERE id='$idFicheFrais'";
				executeSQL($sql);

				addflash('Succ&#232;s', 'Etat de la fiche de frais modifi&#233;.');
			} else {
				addflash('Erreur', 'Cette fiche de frais ne peut pas prendre cette etat.');
			}

			header('location: comptable-rechercheFicheDeFrais.php?visiteurID='.secureDataAAfficher($fichefrais['idVisiteur']).'&mois='.secureDataAAfficher($fichefrais['mois']).'&annee='.secureDataAAfficher($fichefrais['annee']));
			exit;
		break;

	case "VA":
			if($etat == "RB") {
				//update dans la base de donnée
				$sql = "UPDATE fichefrais 
						SET idEtat='$etat' 
						WHERE id='$idFicheFrais'";
				executeSQL($sql);

				addflash('Succ&#232;s', 'Etat de la fiche de frais modifi&#233;.');
			} else {
				addflash('Erreur', 'Cette fiche de frais ne peut pas prendre cette etat.');
			}

			header('location: comptable-rechercheFicheDeFrais.php?visiteurID='.secureDataAAfficher($fichefrais['idVisiteur']).'&mois='.secureDataAAfficher($fichefrais['mois']).'&annee='.secureDataAAfficher($fichefrais['annee']));
			exit;
		break;

	case "RB":
			addflash('Erreur', 'Cette fiche de frais est d&#233;jà rembours&#233;.');
			header('location: comptable-rechercheFicheDeFrais.php?visiteurID='.secureDataAAfficher($fichefrais['idVisiteur']).'&mois='.secureDataAAfficher($fichefrais['mois']).'&annee='.secureDataAAfficher($fichefrais['annee']));
			exit;
		break;

	default:
		
}

?>
