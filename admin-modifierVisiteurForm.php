<?php
@session_start();

require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/config.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if(($_SESSION['login'] != ADMINNAME) && ($_SESSION['login'] != COMPTANAME)) { 	//on vérifie l'utilisateur
	header('location: connexion.php');
	exit;
}

include 'layouts/head.inc.php';
include 'layouts/flash.inc.php';

if($_GET['id'] == "") {
	header('location: admin-listeVisiteur.php');
	exit;
}

$id = secureVariable($_GET['id']);
$sql = "SELECT * FROM Visiteur 
		WHERE id='$id'";

$visiteur = tableSQL($sql)[0];

?>

<a href="admin-listeVisiteur.php" class="backButton"><img class="icone" src="images/icones/back.png" alt="Retour"/></a>

<form method="post" action="admin-modifierVisiteurAction.php">

	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"/>

	<fieldset>
		<legend>Modifier le visiteur <i><?php echo secureDataAAfficher($visiteur['nom'])." ".secureDataAAfficher($visiteur['prenom'])." (".secureDataAAfficher($visiteur['login']).")"; ?></i></legend>
		
		<table>			
			<tr>
				<td><label for="nom">Nom* :</label></td>
				<td><input type="text" name="nom" value="<?php echo secureDataAAfficher($visiteur['nom']); ?>" required /></td>
			</tr>
		
			<tr>
				<td><label for="prenom">Prenom* :</label></td>
				<td><input type="text" name="prenom" value="<?php echo secureDataAAfficher($visiteur['prenom']); ?>" required /></td>
			</tr>
			
			<tr>
				<td><label for="adresse">Adresse :</label></td>
				<td><input type="text" name="adresse" value="<?php echo secureDataAAfficher($visiteur['adresse']); ?>" /></td>
			</tr>
			
			<tr>
				<td><label for="cp">Code postale :</label></td>
				<td><input type="text" name="cp" value="<?php echo secureDataAAfficher($visiteur['cp']); ?>" /></td>
			</tr>
			
			<tr>
				<td><label for="ville">Ville :</label></td>
				<td><input type="text" name="ville" value="<?php echo secureDataAAfficher($visiteur['ville']); ?>" /></td>
			</tr>

			<tr>
				<td><label for="login">Login* :</label></td>
				<td><input type="text" name="login" value="<?php echo secureDataAAfficher($visiteur['login']); ?>" required /></td>
			</tr>

			<tr>
				<td><label for="pwd">Mot de passe :</label></td>
				<td><input type="password" name="pwd" /></td>
			</tr>

			<tr>
				<td><label for="repwd">Retaper le mot de passe :</label></td>
				<td><input type="password" name="repwd" /></td>
			</tr>
			
		
			<tr>
				<tr>
				<td colspan="2"><button class="icone" title="R&#233;initialiser" type="reset"><img src="images/icones/reset.png" alt="R&#233;initialiser"></button><input class="icone" type="image" src="images/icones/save.png" title="Modifier l'utilisateur" alt="Modifier l'utilisateur" /></td>
			</tr>
			</tr>
		
		</table>

	</fieldset>
</form>


<?php

include 'layouts/footer.inc.php';

?>