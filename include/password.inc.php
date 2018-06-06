<?php
function checkPassword($pwd, &$errors) {
	$errors_init = $errors;
	
	if (strlen($pwd) < 8) {
		$errors[] = "Mot de passe trop court (min 8 caractères).";
	}
	
	if (!preg_match("#[0-9]+#", $pwd)) {
		$errors[] = "Le mot de passe doit contenir un nombre.";
	}
	
	if (!preg_match("#[a-zA-Z]+#", $pwd)) {
		$errors[] = "Le mot de passe doit contenir une lettre.";
	}
	
	return ($errors == $errors_init);
}