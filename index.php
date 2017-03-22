<?php

@session_start();

if($_SESSION['login'] == "") {
	header('location: connexion.php');
	exit;
}

include 'layouts/head.inc.php';
include 'layouts/flash.inc.php';

echo "<h2>Bienvenue sur le gestionnaire de fiche de frais de GSB.</h2>";

include 'layouts/footer.inc.php';

?>