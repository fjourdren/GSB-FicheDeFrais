<?php

@session_start();

require_once 'include/AccesDonneesInit.inc.php'; //appelle fichier sql qui se connecte Ã  la bdd
require_once 'include/database.lib.php';
require_once 'include/config.inc.php';

if($_SESSION['login'] == "") {
	header('location: connexion.php');
	exit;
}

include 'layouts/head.inc.php';
include 'layouts/flash.inc.php';

echo "<h2>Bienvenue sur le gestionnaire de fiche de frais de GSB.</h2>";
?>
<br/>
<?php


$sql = "SELECT * FROM historiquePassword
WHERE idVisiteur='".$_SESSION['idVisiteur']."'
ORDER BY updated_date DESC
LIMIT 1";			//requete sql

$resultat = tableSQL($sql);


$dateLimitMotDePasse = strtotime("+".TEMPS_VALIDITE_PASSWORD." days", strtotime($resultat[0]["updated_date"]));
$limit = strtotime(date("Y-m-d", $dateLimitMotDePasse));
$now = strtotime(date("Y-m-d"));

// On récupère la différence de timestamp entre les 2 précédents
$nbJoursTimestamp = $limit - $now;

// ** Pour convertir le timestamp (exprimé en secondes) en jours **
// On sait que 1 heure = 60 secondes * 60 minutes et que 1 jour = 24 heures donc :
$nbJours = $nbJoursTimestamp/86400; // 86 400 = 60*60*24 

echo "Il vous reste $nbJours jours avant le changement obligatoire de votre mot de passe";

include 'layouts/footer.inc.php';

?>