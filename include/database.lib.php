<?php

function secureVariable($var) {
	return addslashes($var);
}

function secureDataAAfficher($var) {
	return htmlentities(utf8_encode($var));
}

?>