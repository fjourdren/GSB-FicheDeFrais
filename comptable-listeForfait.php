<?php
@session_start();

require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/config.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if($_SESSION['login'] != COMPTANAME) { 	//on vérifie que l'utilisateur a le droit d'être sur cette page
	header('location: connexion.php');
	exit;
}

include 'layouts/head.inc.php';
include 'layouts/flash.inc.php';


?>


<a href="comptable-ajouterForfaitForm.php"><img class="icone" src="images/icones/add.png" alt="Ajouter un forfait"/></a>

<table>
	<thead>
		<tr>
			
			<th>ID</th>
			<th>Libelle</th>
			<th>Montant</th>

			<th>Modifier</th>
			<th>Supprimer</th>

		</tr>
	</thead>

	<?php


		$sql = "SELECT * FROM Forfait";			//requete sql qui récupère tous les forfaits
		
		$forfaits = tableSQL($sql);								//execute la requete
				
	
		foreach ($forfaits as $key => $forfaitAAfficher) {
			?>
				<tr>
			
					<td><?php echo secureDataAAfficher($forfaitAAfficher['id']);?></td>
					<td><?php echo secureDataAAfficher($forfaitAAfficher['libelle']);?></td>
					<td><?php echo secureDataAAfficher($forfaitAAfficher['montant']);?>&euro;</td>

					<td>
						<a href="comptable-modifierForfaitForm.php?id=<?php echo secureDataAAfficher($forfaitAAfficher['id']);?>">
							<img class="icone" src="images/icones/edit.png" alt="modifier"/>
						</a>
					</td>

					<td>
						<a href="comptable-supprimerForfait.php?id=<?php echo secureDataAAfficher($forfaitAAfficher['id']);?>" onclick="return confirm('Voulez-vous vraiment supprimer le forfait <?php echo secureDataAAfficher($forfaitAAfficher['libelle']);?> ?')">
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