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
?>


<form action="visiteur-ajouterAction.php" method="post">
	
	<fieldset>
	
		<legend>Ajouter fiche de frais</legend>			
		
		<!-- Gestion du mois par rapport à l'année -->
		<script src="js/formSelectMoisAnnee.js"></script>

		<table border="1">

			<thead>
				<tr>
					<td class="tdTableGauche" colspan="4"><h3>Informations Fiche de frais</h3></td>
				</tr>
			</thead>

			<tbody>

				<?php

					//calcul du mois en cours
					$day   = date("j");
					$month = date("n");
					$year  = date("Y");

					if($day >= NUMERO_JOUR_DE_CLOTURE) {
						$month++;

						if($month >= 12) {
							$month -= 12;
							$year++;
						}
					}

				?>

				<tr>
					<td  class="tdTableGauche"><label for="mois">Mois :</label></td>
					<td>               		
	               		<?php
	               			echo $month;
	               		?>
	               	</td>

	               	<td  class="tdTableGauche"><label for="annee">Ann&#233;e :</label></td>
	                <td>               		
	               		<?php
	               			echo $year;
	               		?>
	               	</td>
				</tr>

	            <tr>
	                <td colspan="2" class="tdTableGauche"><label for="nbJustificatifs">Nombre justificatif :</label></td>
	                <td colspan="2"><input id="nbJustificatifs" name="nbJustificatifs" type="number" min="0" value="0" size="5" /></td>
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
					$sql = "SELECT id, libelle FROM forfait";
					$listeForfaits = tableSQL($sql);

					foreach ($listeForfaits as $key => $forfait) {
						?>

							<tr>
								<td class="tdTableGauche"><label for="<?php echo secureDataAAfficher($forfait['id']);?>"><?php echo secureDataAAfficher($forfait['libelle']);?> : </label></td>
				               	<td><input id="<?php echo secureDataAAfficher($forfait['id']);?>" name="<?php echo secureDataAAfficher($forfait['id']);?>" type="number" min="0" value="0" /></td>
				            </tr>

						<?php
					}
				?>
					
			</tbody>
		</table>

		<br />
		
		<!-- js pour ajouter les inputs hors forfait dynamiquement -->
		<script src="js/horsForfait.js"></script>

		<table border="1">
			<thead>
				<tr>
					<td class="tdTableGauche" colspan="2"><h3>Hors forfaits</h3></td>
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
		
		<table border="1">
			<thead>
				<tr>
					<td class="tdTableGauche" colspan="2"><h3>Sauvegarde</h3></td>
				</tr>
			</thead>
			
			<tbody>
				<tr>
					<td colspan="4"><button class="icone" title="R&#233;initialiser" type="reset"><img src="images/icones/reset.png" alt="R&#233;initialiser"></button><input class="icone" type="image" src="images/icones/save.png" title="Ajouter la fiche de frais" alt="Ajouter la fiche de frais" /></td>
				</tr>
			</tbody>
		</table>

	</fieldset>
</form>


<?php


include 'layouts/footer.inc.php';