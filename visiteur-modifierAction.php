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
$id              = secureVariable($_POST['id']);
$idVisiteur      = secureVariable($_SESSION['idVisiteur']);
$nbJustificatifs = secureVariable($_POST['nbJustificatifs']);


//récupération de la fiche de frais
$sql        = "SELECT * FROM fichefrais 
				WHERE id='$id'
				LIMIT 1";
$fichefrais = tableSQL($sql)[0];


//vérification de l'identité de l'éditeur
if($idVisiteur != $fichefrais['idVisiteur']) {
	addFlash('Erreur', 'vous n\'êtes pas le propri&#233;taire de cette fiche de frais');
	header('location: visiteur-listeFicheFrais.php');
	exit;
}


//vérification de l'état de la fiche de frais
if($fichefrais['idEtat'] != "CR") {
	addFlash('Erreur', 'La fiche de frais est d&#233;j&#224; clotur&#233;');
	header('location: visiteur-listeFicheFrais.php');
	exit;
}



$idFiche = $fichefrais['id'];


$sql = "SELECT idForfait, quantite FROM lignefraisforfait
		WHERE idFicheFrais='$idFiche'";

$lignesFiche = tableSQL($sql);




$sql                 = "SELECT id, montant FROM forfait";
$listeForfaits       = tableSQL($sql);
$montantFicheDeFrais = 0;

foreach ($listeForfaits as $key => $forfait) {

	$forfaitID           = secureVariable($forfait['id']);
	$valeurFormDuForfait = secureVariable($_POST[$forfait['id']]);


	if(isset($valeurFormDuForfait)) {

				if(($valeurFormDuForfait < 0)
					|| (!is_numeric($valeurFormDuForfait))) {
						addFlash('Erreur', 'Les valeurs doivent être des nombres positifs');
						header('location: visiteur-listeFicheFrais.php');
					}

					//on récupère l'ancienne valeur
					$ancienneValeur = -1;
					foreach ($lignesFiche As $ligne) {

						if($ligne['idForfait'] == $forfait['id']) {
							$ancienneValeur = $ligne['quantite'];
							break;
						}
						
					}
					
					
					//choix entre upload et insert en fonction de l'ancienne valeur
					if($ancienneValeur == -1) {
						$sql = "INSERT INTO lignefraisforfait(idFicheFrais, idForfait, quantite)
						VALUES ('$idFiche', '$forfaitID', '$valeurFormDuForfait')";
						executeSQL($sql);
					} else {
						$sql = "UPDATE lignefraisforfait
								SET quantite='$valeurFormDuForfait' 
								WHERE idFicheFrais='$idFiche' 
								AND idForfait='$forfaitID'";
						executeSQL($sql);
					}
						
			}

}

//calcul du montant total de la fiche de frais.
$sql = "SELECT quantite, montant FROM lignefraisforfait, forfait
		WHERE forfait.id = lignefraisforfait.idForfait
		AND lignefraisforfait.idFicheFrais = '$idFiche'";
$lignesFiche = tableSQL($sql);

$montant = 0;
foreach ($lignesFiche As $ligne) {
	$montant = $montant + $ligne['quantite'] * $ligne['montant'];
}




$sqlConcat = "INSERT INTO LigneFraisHorsForfait(idFicheFrais, dteFraisHF, libFraisHF, quantite, montant) VALUES ";

$maxHorsForfait = secureVariable($_POST['horsForfaitNumber']);
for($i = 0; $i < $maxHorsForfait; $i++) {

	$libelleHorsForfait  = secureVariable($_POST["horsForfait".$i."Libelle"]);
	$quantiteHorsForfait = secureVariable($_POST["horsForfait".$i."Quantite"]);
	$montantHorsForfait  = secureVariable($_POST["horsForfait".$i."Montant"]);

	if($montantHorsForfait < 0
			|| !is_numeric($montantHorsForfait)
			|| $quantiteHorsForfait < 0
			|| !is_numeric($quantiteHorsForfait)
			|| !$libelleHorsForfait) {
				addFlash('Erreur', 'Les valeurs de hors forfaits ne sont pas valides.');
				header('location: visiteur-modifierForm.php?id=' + $id);
				exit;
			}
			
			//insert
			if($i != $maxHorsForfait - 1) {
				$sqlConcat .= "('$idFiche', NOW(), '$libelleHorsForfait', '$quantiteHorsForfait', '$montantHorsForfait'), ";
			} else {
				$sqlConcat .= "('$idFiche', NOW(), '$libelleHorsForfait', '$quantiteHorsForfait', '$montantHorsForfait');";
			}
			
			$montant += $montantHorsForfait * $quantiteHorsForfait;
}


//supression des anciennes entr�es hors forfait
$sql = "DELETE FROM LigneFraisHorsForfait WHERE idFicheFrais = '$idFiche'";
executeSQL($sql);


executeSQL($sqlConcat); //ex�cute la commande sql concat�n�



//upload le montant de la fiche de frais et le nombre de justificatif
$sql = "UPDATE fichefrais
		SET montantValide='$montant',
		nbJustificatifs='$nbJustificatifs'
		WHERE id='$id'";
executeSQL($sql);



addFlash('Succ&#232;s', 'Fiche de frais modifi&#233;');
header('location: visiteur-listeFicheFrais.php');
exit;

?>