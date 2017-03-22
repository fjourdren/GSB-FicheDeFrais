<?php
@session_start();

require_once 'include/config.inc.php'; //appelle fichier init pour les variable constante à tout le site
require_once 'include/AccesDonneesInit.inc.php';
require_once 'include/flash.lib.php';
require_once 'include/database.lib.php';

if(($_SESSION['login'] != ADMINNAME) && ($_SESSION['login'] != COMPTANAME)) { 	//on vérifie l'utilisateur
	header('location: connexion.php');
	exit;
}




if(($_POST['nom'] == null) 
	|| ($_POST['prenom'] == null) 
	|| ($_POST['login'] == null) 
	|| ($_POST['pwd'] == null) 
	|| ($_POST['repwd'] == null)) {

	addFlash('Erreur', 'Merci de remplir les champs obligatoires.');
	header('location: admin-ajouterVisiteurForm.php');
	exit;

}

if($_POST['pwd'] != $_POST['repwd']) {
	addFlash('Erreur', 'Les mots de passe ne sont pas identiques.');
	header('location: admin-ajouterVisiteurForm.php');
	exit;
}



$nom     = secureVariable($_POST['nom']);
$prenom  = secureVariable($_POST['prenom']);
$adresse = secureVariable($_POST['adresse']);
$cp      = secureVariable($_POST['cp']);
$ville   = secureVariable($_POST['ville']);
$login   = secureVariable($_POST['login']);
$pwd     = md5($_POST['pwd']);



//V�rification unicit� des logins
$sql = "SELECT login FROM visiteur WHERE login='$login' LIMIT 1";

if(compteSQL($sql) == 1) {
	//mise en session du message flash
	addFlash('Erreur', 'Un visiteur poss&#233;de d&#233;j&#224; ce nom d\'utilisateur.');
	header('location: admin-listeVisiteur.php');
	exit;
}



$sql = "INSERT INTO visiteur(nom, prenom, adresse, cp, ville, login, pwd, DateEmbauche) 
		VALUES('$nom', '$prenom', '$adresse', '$cp', '$ville', '$login', '$pwd', NOW())";

$resultat = executeSQL($sql);



addFlash('Succ&#232;s', 'Visiteur ajout&#233;.');
header('location: admin-listeVisiteur.php');
exit;