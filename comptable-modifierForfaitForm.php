<?php
@session_start();

require_once 'include/config.inc.php';
require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if($_SESSION['login'] != COMPTANAME) { 	//on vérifie que l'utilisateur a le droit d'être sur cette page
	header('location: connexion.php');
	exit;
}

include 'layouts/head.inc.php';
include 'layouts/flash.inc.php';

$id = secureVariable($_GET['id']);

if($id == null) {
	//on met un message flash on redirige vers la liste des forfaits
	addFlash('Erreur', 'Aucun forfait renseign&#233; pour une modification.');
	header('location: comptable-listeForfait.php');
	exit;
}



//on récupére les valeur du forfait
$sql = "SELECT * FROM forfait 
		WHERE id='$id'";

$forfait = ligneSQL($sql);

// Si le forfait n'existe pas on redirige avec une erreur
if($forfait == null) {
	addFlash('Erreur', 'Ce forfait n\'existe pas.');
	header('location: comptable-listeForfait.php');
	exit;
}

?>

<a href="comptable-listeForfait.php" class="backButton" style="text-align: left; display: block;"><img class="icone" src="images/icones/back.png" alt="Retour"/></a>

<form method="post" action="comptable-modifierForfaitAction.php">

	<input type="hidden" name="id" value="<?php echo secureDataAAfficher($forfait[0]); ?>" />

	<fieldset>
		<legend>Modifier le forfait</legend>
		
		<table class="align">
			<tr>
				<td><label for="id">ID* :</label></td>
				<td><p>"<?php echo secureDataAAfficher($forfait[0]); ?>"<p></td>
			</tr>
		
			<tr>
				<td><label for="libelle">Libelle* :</label></td>
				<td><input type="text" name="libelle" required value="<?php echo secureDataAAfficher($forfait[1]); ?>" /></td>
			</tr>
			
			<tr>
				<td><label for="montant">Montant* :</label></td>
				<td><input type="number" name="montant" min="0" value="<?php echo secureDataAAfficher($forfait[2]); ?>" step="any" /></td>
			</tr>
		
			<tr>
				<td colspan="2"><button class="icone" title="R&#233;initialiser" type="reset"><img src="images/icones/reset.png" alt="R&#233;initialiser"></button><input class="icone" type="image" src="images/icones/save.png" title="Modifier le forfait" alt="Modifier le forfait" /></td>
			</tr>
		
		</table>

	</fieldset>
</form>


<?php


include 'layouts/footer.inc.php';