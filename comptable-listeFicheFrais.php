<?php
@session_start();

require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/config.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if($_SESSION['login'] != COMPTANAME) { 	//on vérifie que le visiteur est bien connecté et a le droit
	header('location: connexion.php');
	exit;
}

include 'layouts/head.inc.php';
include 'layouts/flash.inc.php';




//on met toutes les valeurs des forfaits de la fiche dans un tableau unique
$idVisiteur = secureVariable($_SESSION['idVisiteur']);

$sql = "SELECT * FROM fichefrais 
		ORDER BY annee DESC, mois DESC";

$fichesfraisList = tableSQL($sql);

if(count($fichesfraisList) != 0) {
	
	foreach ($fichesfraisList as $keyFiche => $fiche) {
		
		
		//récupération du visiteur
		$idVisiteur = $fiche['idVisiteur'];
		$sql = "SELECT nom, prenom FROM visiteur WHERE id='$idVisiteur'";
		$visiteur = tableSQL($sql)[0];
		$fichesfraisList[$keyFiche]['visiteur'] = $visiteur['prenom']." ".$visiteur['nom'];
		
	
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
				<th>Visiteur</th>
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
				<th>Afficher</th>
			</tr>
		</thead>
	
		<tbody>
			<?php
				foreach ($fichesfraisList as $fiche) {
					
					//affichage
					echo "<tr>";
						echo '<td>'.secureDataAAfficher($fiche['visiteur']).'</td>';
						echo '<td>'.secureDataAAfficher($fiche['mois']).'</td>';
						echo '<td>'.secureDataAAfficher($fiche['annee']).'</td>';
	
						//affichage des toutes les lignes de frais
						foreach ($fiche['lignes'] as $ligne) {
							echo '<td>Quantit&#233;: '.secureDataAAfficher($ligne['quantite']).' (Total: '.secureDataAAfficher($ligne['montant']*$ligne['quantite']).'&euro;)</td>'; 
						}
	
						echo '<td>'.secureDataAAfficher($fiche['nbJustificatifs']).'</td>';
						echo '<td>'.secureDataAAfficher($fiche['montantValide']).'&euro;</td>';
						echo '<td>'.secureDataAAfficher($fiche['etatLibelle']).'</td>';
	
						echo '<td><a href="comptable-rechercheFicheDeFrais.php?visiteurID='.secureDataAAfficher($fiche['idVisiteur']).'&mois='.secureDataAAfficher($fiche['mois']).'&annee='.secureDataAAfficher($fiche['annee']).'"><img class="icone" src="images/icones/show.png" alt="Afficher"/></a></td>';
	
	
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