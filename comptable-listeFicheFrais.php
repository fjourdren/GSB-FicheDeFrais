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

$sql = "SELECT * FROM FicheFrais 
		ORDER BY annee DESC, mois DESC";

$fichesfraisList = tableSQL($sql);

if(count($fichesfraisList) != 0) {
	
	foreach ($fichesfraisList as $keyFiche => $fiche) {
		
		
		//récupération du visiteur
		$idVisiteur = $fiche['idVisiteur'];
		$sql = "SELECT nom, prenom FROM Visiteur WHERE id='$idVisiteur'";
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
	
		$sql = "SELECT libelle FROM Etat 
		WHERE id = '$idEtat' 
		LIMIT 1";
		$fichesfraisList[$keyFiche]['etatLibelle'] = champSQL($sql);

	}
	
	
	
	//affichage de la liste
	?>
	<table>
		<thead>
			<tr>
				<th>Visiteur</th>
				<th>Mois</th>
				<th>Ann&#233;e</th>	
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