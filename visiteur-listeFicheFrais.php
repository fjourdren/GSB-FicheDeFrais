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


//on met toutes les valeurs des forfaits de la fiche dans un tableau unique
$idVisiteur = secureVariable($_SESSION['idVisiteur']);

$sql = "SELECT * FROM fichefrais 
		WHERE idVisiteur = '$idVisiteur' 
		ORDER BY annee DESC, mois DESC";

$fichesfraisList = tableSQL($sql);

if(count($fichesfraisList) != 0) {

	foreach ($fichesfraisList as $keyFiche => $fiche) {
	
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
		$fichesfraisList[$keyFiche]['etatLibelle'] = champSQL($sql);
	
	
	}
	
	
	
	
	
	//affichage de la liste
	?>
	<table border="border">
		<thead>
			<tr>
				<th>Mois</th>
				<th>Ann&#233;e</th>	
				<th>Nombre de justificatif</th>
				<th>Montant</th>
				<th>Etat</th>
				<th>Modifier</th>
				<th>Supprimer</th>
			</tr>
		</thead>
	
		<tbody>
			<?php
				foreach ($fichesfraisList as $fiche) {
	
					//affichage
					echo "<tr>";
						echo '<td>'.secureDataAAfficher($fiche['mois']).'</td>';
						echo '<td>'.secureDataAAfficher($fiche['annee']).'</td>';	
						echo '<td>'.secureDataAAfficher($fiche['nbJustificatifs']).'</td>';
						echo '<td>'.secureDataAAfficher($fiche['montantValide']).'&euro;</td>';
						echo '<td>'.secureDataAAfficher($fiche['etatLibelle']).'</td>';
	
						echo '<td>';
							if($fiche['idEtat'] == "CR") {
								echo '<a href="visiteur-modifierForm.php?id='.secureDataAAfficher($fiche['id']).'">
										<img class="icone" src="images/icones/edit.png" alt="modifier"/>
									</a>';
							} else {
								echo 'L\'etat de la fiche ne permet pas la modification.';
							}
						echo '</td>';
						
						echo '<td>';
						if($fiche['idEtat'] == "CR") {
							echo '<a href="visiteur-supprimerAction.php?id='.secureDataAAfficher($fiche['id']).'">
										<img class="icone" src="images/icones/delete.png" onclick="return confirm(\'Voulez-vous vraiment supprimer cette fiche de frais ?\')" alt="supprimer"/>
									</a>';
						} else {
							echo 'L\'&#233;tat de la fiche ne permet pas la modification.';
						}
						echo '</td>';
	
					echo "</tr>";
				}
			?>
		</tbody>
	</table>
	
	<?php
} else {
	echo "<h3>Aucune fiche de frais cr&eacute;&eacute;e</h3>";
}

include 'layouts/footer.inc.php';