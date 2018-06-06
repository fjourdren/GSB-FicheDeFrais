<?php
session_start();

require_once 'include/AccesDonneesInit.inc.php'; //appelle fichier sql qui se connecte Ã  la bdd
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';
require_once 'include/config.inc.php';
require_once 'include/password.inc.php';


/* verifier si les valeurs existe */
if (($_POST['password'] == null)
		|| ($_POST['repeatpassword'] == null)) {
	//mise en session du message flash
	addFlash('Erreur', 'Merci de remplir tous les champs.');
	
	header('location: visiteur-changerMotDePasseForm.php');							//redirige vers changerMotDePasseForm.php
	exit;
}


$password = md5($_POST['password']);
$repeatpassword = md5($_POST['repeatpassword']);



$errors = array();
checkPassword($_POST['password'], $errors);

if(count($errors) > 0) {
	foreach ($errors as $message) {
		addFlash('Erreur', $message);
	}
	
	header('location: visiteur-changerMotDePasseForm.php');							//redirige vers changerMotDePasseForm.php
	exit;
}



//vérifie si les mots de passes sont identiques
if ($password != $repeatpassword) {
	//mise en session du message flash
	addFlash('Erreur', 'Les mots de passes ne sont pas identiques.');
	
	header('location: visiteur-changerMotDePasseForm.php');							//redirige vers changerMotDePasseForm.php
	exit;
} else {
	$idVisiteur = $_SESSION['idVisiteur'];
	
	
	// vérifie si l'utilisateur a déjà eu ce mot de passe
	$sql = "SELECT * FROM historiquePassword
	WHERE idVisiteur='$idVisiteur'
	AND pwd='$password'";			//requete sql
	
	$resultat = tableSQL($sql);
	
	if(count($resultat) > 0) {
		//mise en session du message flash
		addFlash('Erreur', 'Vous avez déjà eu ce mot de passe.');
		
		header('location: visiteur-changerMotDePasseForm.php');							//redirige vers changerMotDePasseForm.php
		exit;
	} else {
		$sql = "INSERT INTO historiquePassword(idVisiteur, pwd, updated_date)
		VALUES ('$idVisiteur', '$password', NOW())";
		
		executeSQL($sql);
		
		
		header('location: index.php');							//redirige vers index.php
		exit;
	}
	
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