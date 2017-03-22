<?php
@session_start();

require_once 'include/config.inc.php';
require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/flash.lib.php';

if(($_SESSION['login'] != ADMINNAME) && ($_SESSION['login'] != COMPTANAME)) { 	//on vérifie l'utilisateur
	header('location: connexion.php');
	exit;
}

include 'layouts/head.inc.php';
include 'layouts/flash.inc.php';

?>

<a href="admin-listeVisiteur.php" class="backButton" style="text-align: left; display: block;"><img class="icone" src="images/icones/back.png" alt="Retour"/></a>

<form method="post" action="admin-ajouterVisiteurAction.php">

	<fieldset>
		<legend>Ajouter un visiteur</legend>
		
		<table class="align">
			<tr>
				<td><label for="nom">Nom* :</label></td>
				<td><input type="text" name="nom" required /></td>
			</tr>
		
			<tr>
				<td><label for="prenom">Prenom* :</label></td>
				<td><input type="text" name="prenom" required /></td>
			</tr>
			
			<tr>
				<td><label for="adresse">Adresse :</label></td>
				<td><input type="text" name="adresse" /></td>
			</tr>
			
			<tr>
				<td><label for="cp">Code postale :</label></td>
				<td><input type="text" name="cp" /></td>
			</tr>
			
			<tr>
				<td><label for="ville">Ville :</label></td>
				<td><input type="text" name="ville" /></td>
			</tr>

			<tr>
				<td><label for="login">Login* :</label></td>
				<td><input type="text" name="login" required /></td>
			</tr>

			<tr>
				<td><label for="pwd">Mot de passe* :</label></td>
				<td><input type="password" name="pwd" required/></td>
			</tr>

			<tr>
				<td><label for="repwd">Retaper le mot de passe* :</label></td>
				<td><input type="password" name="repwd" required/></td>
			</tr>
			
		
			<tr>
				<td colspan="2"><button class="icone" title="R&#233;initialiser" type="reset"><img src="images/icones/reset.png" alt="R&#233;initialiser"></button><input class="icone" type="image" src="images/icones/save.png" title="Ajouter un utilisateur" alt="Ajouter un utilisateur" /></td>
			</tr>
		
		</table>

	</fieldset>
</form>


<?php

include 'layouts/footer.inc.php';

?>