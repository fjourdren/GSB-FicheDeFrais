<?php
@session_start();

require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';


if($_SESSION['login'] == "") { 	//on vérifie que le visiteur est bien connecté
	header('location: connexion.php');
	exit;
}

include 'layouts/head.inc.php';
include 'layouts/flash.inc.php';

//récup des infos
$id         = secureVariable($_GET['id']);
$idVisiteur = secureVariable($_SESSION['idVisiteur']);


//récupération de la fiche de frais
$sql        = "SELECT * FROM fichefrais 
				WHERE id='$id'
				LIMIT 1";
$fichefrais = tableSQL($sql)[0];





//récupération du libelle de l'état
$idEtat = $fichefrais['idEtat'];

$sql = "SELECT libelle FROM etat 
		WHERE id = '$idEtat' 
		LIMIT 1";
$fichefrais['etatLibelle'] = champSQL($sql);




//vérification de l'identité de l'éditeur
if($idVisiteur != $fichefrais['idVisiteur']) {
	addFlash('Erreur', 'vous n\'&#234;tes pas le propri&#233;taire de cette fiche de frais');
	header('location: visiteur-listeFicheFrais.php');
	exit;
}


//vérification de l'état de la fiche de frais
if($fichefrais['idEtat'] != "CR") {
	addFlash('Erreur', 'La fiche de frais est d&#233;j&#224; clotur&#233;');
	header('location: visiteur-listeFicheFrais.php');
	exit;
}



//on met toutes les valeurs des forfaits de la fiche dans un tableau unique
$sql = "SELECT id, libelle FROM forfait";
$listeForfait = tableSQL($sql);



$sql = "SELECT idForfait, quantite FROM lignefraisforfait 
		WHERE idFicheFrais='$id'";
$listeLignes = tableSQL($sql);


$tableauForfaitAAfficher = array();
foreach ($listeForfait as $key => $forfait) {

	foreach ($listeLignes as $key => $ligne) {

		if($forfait['id'] == $ligne['idForfait']) {

			//on ajoute un autre tableau dans la ligne avec les valeurs correspondante à la bdd
			$tableauForfaitAAfficher[$forfait['id']] = array("id" => $forfait['id'], 
											   "libelle" => $forfait['libelle'], 
											   "quantite" => $ligne['quantite']);
		}

	}


	//Si la valeur n'existe pas, on la met par défaut à 0
	if(!array_key_exists($forfait['id'], $tableauForfaitAAfficher)) {
		$tableauForfaitAAfficher[$forfait['id']] = array("id" => $forfait['id'], 
											   "libelle" => $forfait['libelle'], 
											   "quantite" => 0);
	}

}

?>

<a href="visiteur-listeFicheFrais.php" class="backButton"><img class="icone" src="images/icones/back.png" alt="Retour"/></a>

<form action="visiteur-modifierAction.php" method="post">
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>

	<fieldset>
	
		<legend>Modifier fiche de frais</legend>
		

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
						echo '<td>'.secureDataAAfficher($fichefrais['etatLibelle']).'</td>';
					?>
				</tr>

				<tr>
					<td class="tdTableGauche">Montant</td>
					<?php
						echo '<td>'.secureDataAAfficher($fichefrais['montantValide']).'&euro;</td>';
					?>
				</tr>

				<tr>
					<td class="tdTableGauche">Mois</td>
					<?php
						echo '<td>'.secureDataAAfficher($fichefrais['mois']).'</td>';
					?>
				</tr>

				<tr>
					<td class="tdTableGauche">Ann&#233;e</td>
					<td>
						<?php
							echo secureDataAAfficher($fichefrais['annee']);
						?>
					</td>
				</tr>

				<tr>
	                <td class="tdTableGauche"><label for="nbJustificatifs">Nombre justificatif :</label></td>
	                <td><input id="nbJustificatifs" name="nbJustificatifs" type="number" min="0" value="<?php echo secureDataAAfficher($fichefrais['nbJustificatifs']);?>" size="5" /></td>
	            </tr>

			</tbody>
		</table>

		<br />

		<table border="1">
			<thead>
				<tr>
					<td colspan="2" class="tdTableGauche"><h3>Forfaits</h3></td>
				</tr>
			</thead>
			
			<tbody>
			
			<?php
				foreach ($tableauForfaitAAfficher as $key => $forfaitFicheFrais) {
					?>

						<tr>
							<td class="tdTableGauche">
								<label for="<?php echo secureDataAAfficher($forfaitFicheFrais['id']);?>"><?php echo secureDataAAfficher($forfaitFicheFrais['libelle']);?>: </label>
							</td>
			               	
			               	<td>
			               		<input id="<?php echo secureDataAAfficher($forfaitFicheFrais['id']);?>" name="<?php echo secureDataAAfficher($forfaitFicheFrais['id']);?>" type="number" min="0" value="<?php echo secureDataAAfficher($forfaitFicheFrais['quantite']);?>" />
			               	</td>
			            </tr>

					<?php
				}
			?>

			</tbody>

		</table>
			
		<br />
		
		<script type="text/javascript">

			//mise dans un tableau des hors forfaits pour �tre envoy� au JS
			<?php
				$sql = "SELECT * FROM LigneFraisHorsForfait
				WHERE idFicheFrais='$id'";
				
				$horsForfaits = tableSQL($sql);
				
				$horsForfaitArrayOutput = json_encode($horsForfaits);
			?>
			var map_horsForfait = <?php echo $horsForfaitArrayOutput; ?>;
		</script>
		
		<!-- js pour ajouter les inputs hors forfait dynamiquement -->
		<script src="js/horsForfaitModify.js"></script>
		
		<table border="1">
			<thead>
				<tr>
					<td class="tdTableGauche"><h3>Hors forfaits</h3></td>
				</tr>
			</thead>
			
			<tbody>
				<tr>
					<input id="horsForfaitNumber" name="horsForfaitNumber" type="hidden" value="0" />
					<td><div id="horsForfaitContainer"></div></td>
				</tr>
				
				<tr>
					<td colspan="2">
						<img class="icone" onclick="ajouter_horsForfait()" src="images/icones/add.png" alt="R&#233;initialiser">
			  			<img class="icone" onclick="retirer_horsForfait()" src="images/icones/remove.png" alt="R&#233;initialiser">
			  		</td>
				</tr>
			</tbody>
		
		</table>

		<br />
		
		<table class="align">
			<tr>
				<td class="tdTableGauche"><h3>Sauvegarde</h3></td>
			</tr>

			<tr>
				<td><button class="icone" title="R&#233;initialiser" type="reset"><img src="images/icones/reset.png" alt="R&#233;initialiser"></button><input class="icone" type="image" src="images/icones/save.png" title="Ajouter la fiche de frais" alt="Ajouter la fiche de frais" /></td>
			</tr>
		</table>

	</fieldset>
</form>



<?php
include 'layouts/footer.inc.php';