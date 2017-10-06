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


?>


<a href="admin-ajouterVisiteurForm.php"><img class="icone" src="images/icones/add.png" alt="Ajouter un visiteur"/></a>

<table>
	<thead>
		<tr>
			
			<th>ID</th>
			<th>Nom</th>
			<th>Prenom</th>
			<th>Adresse</th>
			<th>Code postal</th>
			<th>Ville</th>
			<th>Date d'embauche</th>

			<th>Modifier</th>
			<th>Supprimer</th>

		</tr>
	</thead>

	<?php


		$sql = "SELECT * FROM Visiteur";			//requete sql qui récupère tous les visiteurs
		
		$visiteurs = tableSQL($sql);								//execute la requete
				
	
		foreach ($visiteurs as $key => $visiteur) {
			?>
				<tr>
			
					<td><?php echo secureDataAAfficher($visiteur['id']);?></td>
					<td><?php echo secureDataAAfficher($visiteur['nom']);?></td>
					<td><?php echo secureDataAAfficher($visiteur['prenom']);?></td>
					<td><?php echo secureDataAAfficher($visiteur['adresse']);?></td>
					<td><?php echo secureDataAAfficher($visiteur['cp']);?></td>
					<td><?php echo secureDataAAfficher($visiteur['ville']);?></td>
					<td><?php echo date('d-m-Y', strtotime($visiteur['dateEmbauche']));?></td>

					<td>
						<a href="admin-modifierVisiteurForm.php?id=<?php echo secureDataAAfficher($visiteur['id']);?>">
							<img class="icone" src="images/icones/edit.png" alt="modifier"/>
						</a>
					</td>

					<td>
						<a href="admin-supprimerVisiteur.php?id=<?php echo secureDataAAfficher($visiteur['id']);?>" onclick="return confirm('Voulez-vous vraiment supprimer l\'utilisateur <?php echo secureDataAAfficher($visiteur['nom']).' '.secureDataAAfficher($visiteur['prenom']);?> ?')">
							<img class="icone" src="images/icones/delete.png" alt="supprimer"/>
						</a>
					</td>

				</tr>
			<?php
		}

	?>
</table>

<?php


include 'layouts/footer.inc.php';