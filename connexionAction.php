<?php
session_start();

require_once 'include/AccesDonneesInit.inc.php'; //appelle fichier sql qui se connecte à la bdd
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';
require_once 'include/config.inc.php';


/* verifier si les valeurs existe */
if (($_POST['password'] == null) 
	|| ($_POST['pseudo'] == null)) {
	//mise en session du message flash
	addFlash('Erreur', 'Merci de remplir tous les champs.');

	header('location: connexion.php');							//redirige vers login.php
	exit;
}

$pseudo   = secureVariable($_POST['pseudo']);
$password = md5($_POST['password']);

$sql = "SELECT * FROM Visiteur 
		WHERE login='$pseudo' 
		LIMIT 1";			//requete sql

$resultat = tableSQL($sql);								//execute la requete

if (count($resultat) != 1) {					//	compte le nombre de resultat
	//mise en session du message flash
	addFlash('Erreur', 'Cet utilisateur n\'existe pas.');

	header('location: connexion.php');							//redirige vers login.php
	exit;
}





$id = secureVariable($resultat[0]['id']);

$sql = "SELECT * FROM historiquePassword
		WHERE idVisiteur='$id' 
		ORDER BY updated_date DESC 
		LIMIT 1";			//requete sql

$resultat = tableSQL($sql);	

$dateDuDernierMotDePasse = $resultat[0]["updated_date"];

if ($resultat[0]["pwd"] != $password) {					//	compte le nombre de resultat
	//mise en session du message flash
	addFlash('Erreur', 'Mot de passe incorrect.');
	
	header('location: connexion.php');							//redirige vers login.php
	exit;
}





$_SESSION['login']      = secureVariable($pseudo);							//stock donner de $pseudo dans $_session
$_SESSION['idVisiteur'] = secureVariable($id);				//stock l'id du visiteur dans la session





$dateLimitMotDePasse= strtotime("+".TEMPS_VALIDITE_PASSWORD." days", strtotime($dateDuDernierMotDePasse));

$now = date("Y-m-d");
$limit = date("Y-m-d", $dateLimitMotDePasse);
if($now >= $limit) {
	header('location: visiteur-changerMotDePasseForm.php');							//redirige vers index.php
	exit;
} else {
	header('location: index.php');							//redirige vers index.php
	exit;
}