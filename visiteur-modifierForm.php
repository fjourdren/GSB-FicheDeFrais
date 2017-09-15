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
				WHERE id='$id'";
$fichefrais = tableSQL($sql)[0];


//vérification de l'identité de l'éditeur
if($idVisiteur != $fichefrais['idVisiteur']) {
	addFlash('Erreur', 'vous n\' 	&#234;tes pas le propri&#233;taire de cette fiche de frais');
	header('location: visiteur-listeFicheFrais.php');
	exit;
}


//vérification de l'état de la fiche de frais
if($fichefrais['idEtat']!="CR") {
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
			
		<table class="align">
			
			<?php
				foreach ($tableauForfaitAAfficher as $key => $forfaitFicheFrais) {
					?>

						<tr>
							<td colspan="2"><label for="<?php echo secureDataAAfficher($forfaitFicheFrais['id']);?>"><?php echo secureDataAAfficher($forfaitFicheFrais['libelle']);?>: </label></td>
			               	<td colspan="2"><input id="<?php echo secureDataAAfficher($forfaitFicheFrais['id']);?>" name="<?php echo secureDataAAfficher($forfaitFicheFrais['id']);?>" type="number" min="0" value="<?php echo secureDataAAfficher($forfaitFicheFrais['quantite']);?>" /></td>
			            </tr>

					<?php
				}
			?>

            <tr>
                <td colspan="2"><label for="nbJustificatifs">Nombre justificatif :</label></td>
                <td colspan="2"><input id="nbJustificatifs" name="nbJustificatifs" type="number" min="0" value="<?php echo secureDataAAfficher($fichefrais['nbJustificatifs']);?>" size="5" /></td>
            </tr>

		</table>
			
		<br />
		
		<table class="align">
			<tr>
				<td colspan="2"><h3>Hors forfaits</h3></td>
			</tr>
		
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
		
		</table>
		
		<!-- js pour ajouter les inputs hors forfait dynamiquement -->
		<script src="js/horsForfait.js"></script>
		
		<br />
		
		<table class="align">
			<tr>
				<td colspan="4"><button class="icone" title="R&#233;initialiser" type="reset"><img src="images/icones/reset.png" alt="R&#233;initialiser"></button><input class="icone" type="image" src="images/icones/save.png" title="Ajouter la fiche de frais" alt="Ajouter la fiche de frais" /></td>
			</tr>
		</table>

	</fieldset>
</form>



<?php
include 'layouts/footer.inc.php';