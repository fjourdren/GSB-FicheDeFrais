<?php
@session_start();

require_once 'include/config.inc.php'; //appelle fichier init pour les bariable constante à tout le site
require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if(($_SESSION['login'] != ADMINNAME) && ($_SESSION['login'] != COMPTANAME)) { 	//on vérifie l'utilisateur
	header('location: connexion.php');
	exit;
}

if($_POST['id'] == "") {
	addFlash('Erreur', 'Aucun ID renseign&#233;');
	header('location: admin-listeVisiteur.php');
	exit;
}

if(($_POST['id']==null) 
	|| ($_POST['nom'] == null) 
	|| ($_POST['prenom'] == null) 
	|| ($_POST['login'] == null)) {
	//mise en session du message flash
	addFlash('Erreur', 'Merci de remplir tous les champs.');
	header('location: admin-modifierVisiteurForm.php?id='.$id.'');
	exit;
}


$id = secureVariable($_POST['id']);




if($_POST['pwd'] == null) {

	$sql = "SELECT * FROM visiteur 
			WHERE id='$id' 
			LIMIT 1";			//requete sql qui récupère le visiteur

	$pwd = secureVariable(tableSQL($sql)[0]['pwd']);								//execute la requete
	
} else {

	if($_POST['pwd'] != $_POST['repwd']) {
		//mise en session du message flash
		addFlash('Erreur', 'Les mots de passe ne sont pas identiques');
		header('location: admin-listeVisiteur.php');
		exit;
	}

	$pwd = md5($_POST['pwd']);
}




$nom     = secureVariable($_POST['nom']);
$prenom  = secureVariable($_POST['prenom']);
$adresse = secureVariable($_POST['adresse']);
$cp      = secureVariable($_POST['cp']);
$ville   = secureVariable($_POST['ville']);
$login   = secureVariable($_POST['login']);



//V�rification unicit� des logins
$sql = "SELECT login FROM visiteur WHERE login='$login' AND id <> '$id' LIMIT 1";

if(compteSQL($sql) == 1) {
	//mise en session du message flash
	addFlash('Erreur', 'Un visiteur poss&#233;de d&#233;j&#224; ce nom d\'utilisateur.');
	header('location: admin-listeVisiteur.php');
	exit;
}


$sql = "UPDATE visiteur 
		SET nom='$nom', prenom='$prenom', adresse='$adresse', cp='$cp', ville='$ville', login='$login', pwd='$pwd' 
		WHERE id='$id'";

$resultat = executeSQL($sql);


//mise en session du message flash
addFlash('Succ&#232;s', 'Visiteur modifi&#233;');
header('location: admin-listeVisiteur.php');
exit;