<?php
@session_start();

require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/config.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if($_SESSION['login'] == "") { 	//on vérifie que le visiteur est bien connecté
	header('location: connexion.php');
	exit;
}

include 'layouts/head.inc.php';
include 'layouts/flash.inc.php';

if($_GET['id'] == "") { 	//on vérifie que l'id de la fiche est renseigné
	addFlash('Erreur', 'Cet fiche de frais n\'existe pas.');
	header('location: visiteur-listeFicheFrais.php');
	exit;
}

?>

<a href="visiteur-listeFicheFrais.php" class="backButton"><img class="icone" src="images/icones/back.png" alt="Retour"/></a>
	
<?php

	$id = secureVariable($_GET['id']);
	

	//récupération des infos dans la base de donnée	
	$sql = "SELECT * FROM fichefrais 
			WHERE id='$id' 
			LIMIT 1";

	// on vérifie que la fiche existe avant l'affichage
	if(compteSQL($sql) != 0) {
		$fiche = tableSQL($sql)[0]; // on récupère la seul fiche retourné


		//vérification de l'identité de l'éditeur
		$idVisiteur = secureVariable($_SESSION['idVisiteur']);
		if($idVisiteur != $fiche['idVisiteur']) {
			addFlash('Erreur', 'vous n\'êtes pas le propri&#233;taire de cette fiche de frais');
			header('location: visiteur-listeFicheFrais.php');
			exit;
		}


		//on retire les id numériques du tableau
		foreach ($fiche as $key => $valeurDansTableau) {
			if(is_numeric($key)) {
				unset($fiche[$key]);
			}
		}



		//récupération du libelle de l'état
		$idEtat = $fiche['idEtat'];

		$sql = "SELECT libelle FROM etat 
				WHERE id = '$idEtat' 
				LIMIT 1";
		$fiche['etatLibelle'] = champSQL($sql);


		$sql          = "SELECT * FROM forfait";
		$listeForfait = tableSQL($sql);


		foreach ($listeForfait as $keyForfait => $forfait) {

			//on retire les id numériques du tableau
			foreach ($forfait as $key => $valueForfait) {
				if(is_numeric($key)) {
					unset($listeForfait[$keyForfait][$key]);
				}
			}


			//on récupére les de chaque champ pour chaque fiche de frais
			$idFiche   = $fiche['id'];
			$idForfait = $forfait['id'];


			//on récupére le montant et la quantité et on les mets dans un tableau
			$sql = "SELECT quantite FROM lignefraisforfait
					WHERE idFicheFrais = '$idFiche'
					AND idForfait = '$idForfait'";
			$quantite = secureVariable(champSQL($sql));

			if(!$quantite)
				$quantite = 0;


			$fiche['lignes'][] = array("forfait" => $idForfait, "libelle" => $forfait['libelle'], "quantite" => $quantite, "montant" => $forfait['montant']);

		}




		//hors forfait
		$sql = "SELECT * FROM LigneFraisHorsForfait
				WHERE idFicheFrais = '$idFiche'";

		$horsForfaitsResult = tableSQL($sql);

		foreach ($horsForfaitsResult as $key => $horsForfaitItem) {
		$fiche['lignesFraisHorsForfait'][] = array("libelle" => $horsForfaitItem['libFraisHF'], "quantite" => $horsForfaitItem['quantite'], "montant" => $horsForfaitItem['montant']);
		}



?>

		<table border="1">
			<thead>
				<tr>
					<td class="tdTableGauche" colspan="2"><h3>Informations Fiche de frais</h3></td>
				</tr>
			</thead>
		
			<tbody>
			
				<tr>
					<td class="tdTableGauche">&#201;tat</td>
					<?php
						echo '<td>'.secureDataAAfficher($fiche['etatLibelle']).'</td>';
					?>
				</tr>

				<tr>
					<td class="tdTableGauche">Montant</td>
					<?php
						echo '<td>'.secureDataAAfficher($fiche['montantValide']).'&euro;</td>';
					?>
				</tr>

				<tr>
					<td class="tdTableGauche">Mois</td>
					<?php
						echo '<td>'.secureDataAAfficher($fiche['mois']).'</td>';
					?>
				</tr>

				<tr>
					<td class="tdTableGauche">Année</td>
					<?php
						echo '<td>'.secureDataAAfficher($fiche['annee']).'</td>';
					?>
				</tr>

				<tr>
	                <td class="tdTableGauche"><label for="nbJustificatifs">Nombre justificatif :</label></td>
	                <td><?php echo secureDataAAfficher($fiche['nbJustificatifs']);?></td>
	            </tr>

			</tbody>
		</table>

		<br />
		
		<table border="1">
		
			<thead>
				<tr>
					<td class="tdTableGauche" colspan="2"><h3>Forfaits</h3></td>
				</tr>
			</thead>
		
			<tbody>
					<?php
					
					//affichage du contenu
					foreach ($fiche['lignes'] as $ligne) {
						echo '<tr>
							<td class="tdTableGauche">'.secureDataAAfficher($ligne['libelle'])." <br/>(".secureDataAAfficher($ligne['montant']).'&euro;)</td>
							<td>Quantit&#233;: '.secureDataAAfficher($ligne['quantite']).' <br/>(Total: '.secureDataAAfficher($ligne['quantite']*$ligne['montant']).'&euro;)</td>
						</tr>';
					}
					

					echo '<tr>
						<td class="tdTableGauche">Etat de la fiche de frais</td>
						<td>'.secureDataAAfficher($fiche['etatLibelle']).'</td>
					</tr>';

				?>
					
			</tbody>
		</table>
		
		<br />
		
		<table border="1">
			
			<thead>
				<tr>
					<td class="tdTableGauche" colspan="2"><h3>Hors forfaits</h3></td>
				</tr>
			</thead>
		
			<tbody>
					<?php
					
					//affichage du contenu
					foreach ($fiche['lignesFraisHorsForfait'] as $ligne) {
						echo '<tr>
							<td class="tdTableGauche">'.secureDataAAfficher($ligne['libelle'])." <br/>(".secureDataAAfficher($ligne['montant']).'&euro;)</td>
							<td>Quantit&#233;: '.secureDataAAfficher($ligne['quantite']).' <br/>(Total: '.secureDataAAfficher($ligne['quantite']*$ligne['montant']).'&euro;)</td>
						</tr>';
					}
					

					

				?>
					
			</tbody>
		</table>

		<?php

	} else {
		echo "<h3>Cette fiche de frais n'existe pas</h3>";
	}


	include 'layouts/footer.inc.php';
?>