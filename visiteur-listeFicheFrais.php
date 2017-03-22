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


	$sql          = "SELECT * FROM forfait";
	$listeForfait = tableSQL($sql);

	
	foreach ($listeForfait as $keyForfait => $forfait) {

		//on retire les id numériques du tableau
		foreach ($forfait as $key => $valueForfait) {
			if(is_numeric($key)) {
				unset($listeForfait[$keyForfait][$key]);
			}
		}


		//on récupére les de chaque champ pour chaque fiche de frais
		$idFiche   = $fiche['id'];
		$idForfait = $forfait['id'];


		//on récupére le montant et la quantité et on les mets dans un tableau
		$sql = "SELECT quantite FROM lignefraisforfait
				WHERE idFicheFrais = '$idFiche'
				AND idForfait = '$idForfait'";
		$quantite = secureVariable(champSQL($sql));

		if(!$quantite)
			$quantite = 0;


		$fichesfraisList[$keyFiche]['lignes'][] = array("forfait" => $idForfait, "quantite" => $quantite, "montant" => $forfait['montant']);

	}

}





//affichage de la liste
?>
<table border="border">
	<thead>
		<tr>
			<th>Mois</th>
			<th>Ann&#233;e</th>

			<?php
				foreach ($listeForfait as $forfait) {
					echo '<th>'.secureDataAAfficher($forfait['libelle']).' ('.secureDataAAfficher($forfait['montant']).'&euro;)</th>';
				}
			?>

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

					//affichage des toutes les lignes de frais
					foreach ($fiche['lignes'] as $ligne) {
						echo '<td>Quantit&#233;: '.secureDataAAfficher($ligne['quantite']).' (Total: '.secureDataAAfficher($ligne['montant']*$ligne['quantite']).'&euro;)</td>'; 
					}

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


include 'layouts/footer.inc.php';