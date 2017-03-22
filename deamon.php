<?php

require_once 'include/config.inc.php';

if($_GET['pwd'] != PWD_DEAMON) {
	header('location: index.php');
	exit;
}

require_once 'include/AccesDonneesInit.inc.php';

//vérification de la date du jour
if(date('j') == NUMERO_JOUR_DE_CLOTURE) {
	$mois  = date('n');
	$annee = date('Y');

	//mise en cloture des fiches du mois
	$sql = "UPDATE fichefrais 
			SET idEtat='CL' 
			WHERE mois='$mois' 
			AND annee='$annee'";
	executeSQL($sql);

	echo "Fiches cloturées<br/>";
}

echo "Fin de l'exécution<br/>";

?>