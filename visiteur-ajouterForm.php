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

		<table class="align">
			<tr>
				<td><label for="mois">Mois (2 chiffres):</label></td>
				<td>               		
               		<select name="mois" id="mois">
					<?php

						//géré par le javascript formSelectMoisAnnee.js
					  	/*for($m = 1; $m <= 12; $m++) {
               			?>
               			
               				<option 
	               				<?php 
	               					if ($m == date('m')) {
	               						echo 'selected="selected"';
	               					}
	               				?>
               				value="<?php echo $m; ?>"><?php echo $m; ?></option>

               			<?php 
               			}*/
               			?>
					</select>
               	</td>
                	
               	<td><label for="annee">Ann&#233;e (4 chiffres):</label></td>
                <td>               		
               		<select name="annee" id="annee">
					  <?php
					  
					 $sql = "SELECT min(annee) FROM fichefrais LIMIT 1";
					  
					 $minAnnee = champSQL($sql);
					  if($minAnnee == null)
					  	$minAnnee = date("Y")-1;
					  
						for($Y = $minAnnee; $Y <= date("Y"); $Y++) {
               			?>

               				<option 
	               				<?php 
	               					if ($Y == date('Y')) {
	               						echo 'selected="selected"';
	               					}
	               				?>
               				value="<?php echo $Y; ?>"><?php echo $Y; ?></option>

               			<?php 
               			}
               			?>
					</select>
               	</td>
			</tr>

			<?php
				$sql = "SELECT id, libelle FROM forfait";
				$listeForfaits = tableSQL($sql);

				foreach ($listeForfaits as $key => $forfait) {
					?>

						<tr>
							<td colspan="2"><label for="<?php echo secureDataAAfficher($forfait['id']);?>"><?php echo secureDataAAfficher($forfait['libelle']);?>: </label></td>
			               	<td colspan="2"><input id="<?php echo secureDataAAfficher($forfait['id']);?>" name="<?php echo secureDataAAfficher($forfait['id']);?>" type="number" min="0" value="0" /></td>
			            </tr>

					<?php
				}
			?>

            <tr>
                <td colspan="2"><label for="nbJustificatifs">Nombre justificatif :</label></td>
                <td colspan="2"><input id="nbJustificatifs" name="nbJustificatifs" type="number" min="0" value="0" size="5" /></td>
            </tr>
				
		</table>
		
		<br />
		
		<!-- js pour ajouter les inputs hors forfait dynamiquement -->
		<script src="js/horsForfait.js"></script>

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