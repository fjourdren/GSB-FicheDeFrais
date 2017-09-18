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

include 'layouts/head.inc.php';
include 'layouts/flash.inc.php';

?>

<a href="comptable-listeFicheFrais.php" class="backButton"><img class="icone" src="images/icones/back.png" alt="Retour"/></a>

<form class="comptableForm" id="formulaire" action="" method="get">
	
	<fieldset>
		
	<legend>Consulter une fiche de frais</legend>

		<table class="align">
			<tr>
				<td><label for="visiteurID">Visiteur :</label></td>
		    	<td><select name="visiteurID">
						<?php
						
							//récupérer tous les utilisateurs et les affiches dans un select
							$sql = "SELECT id, nom, prenom, login 
									FROM visiteur 
									ORDER BY nom"; 			
							$resultDataVisiteurs = tableSQL($sql);
							
							foreach($resultDataVisiteurs AS $visiteur) {

								//affiche l'option et la met par défaut si on cherche déjà dans la bdd
								if($_GET['visiteurID'] == $visiteur['id']) {
									echo '<option selected="selected" value="'.secureDataAAfficher($visiteur['id']).'">'.secureDataAAfficher($visiteur['nom']).' '.secureDataAAfficher($visiteur['prenom']).' ('.secureDataAAfficher($visiteur['login']).')</option>';
								} else {
									echo '<option value="'.secureDataAAfficher($visiteur['id']).'">'.secureDataAAfficher($visiteur['nom']).' '.secureDataAAfficher($visiteur['prenom']).' ('.secureDataAAfficher($visiteur['login']).')</option>';
								}
								
							}
						
						?>
						
					</select></td>
			</tr>
			
			<tr>
				<td><label for="mois">Date :</label></td>
		    	<td>
		    		<select name="mois" id="mois">

						<?php
							//géré via js donc commenté
						
						  	/*for($m = 1; $m <=12; $m++) {

							  	//gestion de la valeur du mois par défaut
							  	if(@$_GET['mois'] != null) {
									if($_GET['mois'] == $m) {
										echo '<option selected="selected" value="'.$m.'">'.$m.'</option>';
									} else {
										echo '<option value="'.$m.'">'.$m.'</option>';
									}
								} else {
									if($m == date('m')) {
										echo '<option selected="selected" value="'.$m.'">'.$m.'</option>';
									} else {
										echo '<option value="'.$m.'">'.$m.'</option>';
									}
								}
							}*/

						?>

					</select>
				
		    		<select name="annee" id="annee">
						<?php
						
							$sql = "SELECT DISTINCT annee 
									FROM fichefrais 
									ORDER BY annee";        //récupérer toutes les années qui possède une fiche de frais
							$resultDataAnnees = tableSQL($sql);
							
							foreach($resultDataAnnees AS $anneeData) {					//affichage de toutes les données

								//gestion de la valeur de l'année par défaut
								if($_GET['annee'] != null) {
									if($_GET['annee'] == $anneeData['annee']) {
										echo '<option selected="selected" value="'.secureDataAAfficher($anneeData['annee']).'">'.secureDataAAfficher($anneeData['annee']).'</option>';
									} else {
										echo '<option value="'.secureDataAAfficher($anneeData['annee']).'">'.secureDataAAfficher($anneeData['annee']).'</option>';
									}
								} else {
									if($anneeData['annee'] == date('Y')) {
										echo '<option selected="selected" value="'.secureDataAAfficher($anneeData['annee']).'">'.secureDataAAfficher($anneeData['annee']).'</option>';
									} else {
										echo '<option value="'.secureDataAAfficher($anneeData['annee']).'">'.secureDataAAfficher($anneeData['annee']).'</option>';
									}
								}

								
							}
						
						?>
						
					</select>
				</td>
		    </tr>

			<tr>
				<td colspan="2"><input class="icone" type="image" src="images/icones/show.png" title="Consulter" alt="Consulter" /></td>
			</tr>
			
		</table>
		
		<!-- Gestion du mois par rapport à l'année -->
		<script src="js/formSelectMoisAnnee.js"></script>

	</fieldset>

</form>

<br/>
	
<?php

	if(isset($_GET['visiteurID']) 
		&& isset($_GET['mois']) 
		&& isset($_GET['annee'])) {
		
		$visiteurID = secureVariable($_GET['visiteurID']);
		$mois       = secureVariable($_GET['mois']);
		$annee      = secureVariable($_GET['annee']);
		

		//récupération des infos dans la base de donnée	
		$sql = "SELECT * FROM fichefrais 
				WHERE mois='$mois' 
				AND annee='$annee' 
				AND idVisiteur='$visiteurID' 
				LIMIT 1";

		// on vérifie que la fiche existe avant l'affichage
		if(compteSQL($sql)!=0) {
			$fiche = tableSQL($sql)[0]; // on récupère la seul fiche retourné



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
					<td class="tdTableGauche" colspan="2"><h3>Information Fiche de frais</h3></td>
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
			
			<br>
			
			<table border="1">
				<thead>
					<tr>
						<td class="tdTableGauche" colspan="2"><h3>Changement d'&#233;tat</h3></td>
					</tr>
				</thead>
			
				<tbody>

					<tr>
						<td>
	
							<?php
								if($fiche['idEtat'] != "CR") {
	
									switch($fiche['idEtat']) {
										case "CL":
											echo '<a href="comptable-changerEtatAction.php?idFicheFrais='.secureDataAAfficher($fiche['id']).'&etat=VA"><h3>Valider la fiche de frais</h3></a>';
											break;
										case "VA":
											echo '<a href="comptable-changerEtatAction.php?idFicheFrais='.secureDataAAfficher($fiche['id']).'&etat=RB"><h3>Passer la fiche de frais en &#233tat "Rembours&#233;"</h3></a>';
											break;
										case "RB":
											echo "<h3>Cette fiche est d&#233;j&agrave; rembours&#233;</h3>";
											break;
										default:
											echo "<h3>Erreur d'état de la fiche de frais</h3>";
									}
	
								} else {
									echo "<h3>Cette fiche n'est pas encore clotur&#233;.</h3>";
								}
							?>
	
						</td>
					</tr>
				</tbody>
			</table>

			<?php

		} else {
			echo "<h3>Cette fiche de frais n'existe pas</h3>";
		}
	}


	include 'layouts/footer.inc.php';
?>