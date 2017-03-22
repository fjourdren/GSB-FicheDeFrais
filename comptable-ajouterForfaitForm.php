<?php
@session_start();

require_once 'include/config.inc.php';
require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/flash.lib.php';

if($_SESSION['login'] != COMPTANAME) { 	//on vérifie que l'utilisateur a le droit d'être sur cette page
	header('location: connexion.php');
	exit;
}

include 'layouts/head.inc.php';
include 'layouts/flash.inc.php';

?>

<a href="comptable-listeForfait.php" class="backButton" style="text-align: left; display: block;"><img class="icone" src="images/icones/back.png" alt="Retour"/></a>

<form method="post" action="comptable-ajouterForfaitAction.php">

	<fieldset>
		<legend>Ajouter un forfait</legend>
		
		<table class="align">
			<tr>
				<td><label for="id">ID* :</label></td>
				<td><input type="text" name="id" required maxlength="3" /></td>
			</tr>
		
			<tr>
				<td><label for="libelle">Libelle* :</label></td>
				<td><input type="text" name="libelle" required /></td>
			</tr>
			
			<tr>
				<td><label for="montant">Montant* :</label></td>
				<td><input type="number" name="montant" required min="0" value="0" step="any" /></td>
			</tr>
		
			<tr>
				<td colspan="2"><button class="icone" title="R&#233;initialiser" type="reset"><img src="images/icones/reset.png" alt="R&#233;initialiser"></button><input class="icone" type="image" src="images/icones/save.png" title="Ajouter le forfait" alt="Ajouter le forfait" /></td>
			</tr>
		
		</table>

	</fieldset>
</form>


<?php


include 'layouts/footer.inc.php';