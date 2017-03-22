<?php
@session_start();

require_once 'include/config.inc.php'; //apelle du dichier de config
?>

<!-- MENU -->
<div class="menu">

	<!-- LOGO -->
	<img class="logo" src="images/logo.png" alt="LOGO GSB" />

	<?php
		//gestions des droits dans le menu
		if(($_SESSION['login'] == ADMINNAME) || ($_SESSION['login'] == COMPTANAME)) {
	?>

		<ul class="navigation">
			<li class="title"><h3>Module d'Administration</h3></li>
			<li><a href="admin-listeVisiteur.php">Liste des utilisateurs</a></li>
			<li><a href="admin-ajouterVisiteurForm.php">Ajouter un utilisateur</a></li>
		</ul>
		
	<?php
		}
	?>



	<?php
		if($_SESSION['login'] == COMPTANAME) {
	?>

		<ul class="navigation">
			<li class="title"><h3>Module Comptable</h3></li>
			<li><a href="comptable-rechercheFicheDeFrais.php">Recherche fiche de frais</a></li>
			<li><a href="comptable-listeFicheFrais.php">Liste fiche de frais</a></li>
			<li><a href="comptable-listeForfait.php">Modification des tarifs</a></li>
		</ul>
			
	<?php
		}
	?>

	<ul class="navigation">
		<li class="title"><h3>Module Visiteur</h3></li>
		<li><a href="visiteur-ajouterForm.php">Nouvelle fiche de frais</a></li>
			<li><a href="visiteur-listeFicheFrais.php">Mes fiches de frais</a></li>
	</ul>

	<ul class="navigation">
		<li class="title"><h3>Autre</h3></li>
		<li class="other"><?php echo $_SESSION['login'];?></li>
		<li><a href="deconnexion.php">D&#233;connexion</a></li>
	</ul>

</div>