<?php
@session_start();

require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/config.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if($_SESSION['login'] == "") { 	//on vÃ©rifie que le visiteur est bien connectÃ©
	header('location: connexion.php');
	exit;
}

include 'layouts/head.inc.php';
include 'layouts/flash.inc.php';
?>


<form action="visiteur-changerMotDePasseAction.php" method="post">
	
	<fieldset>
	
		<legend>Modifier le mot de passe</legend>			
		
		<table>

			<thead>
				<tr>
					<td class="tdTableGauche" colspan="4"><h3>Mot de passe</h3></td>
				</tr>
			</thead>

			<tbody>

	            <tr>
	                <td colspan="2" class="tdTableGauche"><label for="password">Nouveau mot de passe* :</label></td>
	                <td colspan="2"><input id="password" name="password" type="password"/></td>
	            </tr>
	            
	            <tr>
	                <td colspan="2" class="tdTableGauche"><label for="repeatpassword">Répéter le Nouveau mot de passe* :</label></td>
	                <td colspan="2"><input id="repeatpassword" name="repeatpassword" type="password"/></td>
	            </tr>
	            
	            <tr>
	            	<td colspan="4"><input class="icone" type="image" src="images/icones/save.png" title="Ajouter la fiche de frais" alt="Ajouter la fiche de frais" /></td>
				</tr>
            </tbody>
				
		</table>

	</fieldset>
</form>


<?php


include 'layouts/footer.inc.php';