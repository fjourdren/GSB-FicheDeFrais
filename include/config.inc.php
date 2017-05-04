<?php
define('ADMINNAME', 'admin');
define('COMPTANAME', 'comptable');

define('NUMERO_JOUR_DE_CLOTURE', 20);

define("PWD_DEAMON", "pP985y5kbwDjPLRQ5fFJ7pLsF3f96amvsqrgA7t6yK3cNzk2zHiqdQK9f3sZz492zXXd3FA5jYZ7cnzu794RHEemR5U68faLUSZeV24AeA3FwUL67Fv4tVV29h6P5Vkj2h58j4VdJ5p5g5343N7z22S9fs4L7X38YL3d2tAzhc3xY4TiZ3SX48Zw72PZN8EaqhJqF2D5SGJ2");

if(php_sapi_name() === 'cli') {
	$host = false;
} else {
	$host = $_SERVER["HTTP_HOST"];
}

switch ($host) {

	case 'localhost':
	case 'localhost:800':
	case '127.0.0.1':
		define("MysqlURL", "localhost");
		define("MysqlUSER", "root");
		define("MysqlPWD", "");
		define("MysqlDATABASE", "gsb");
		define("MysqlPORT", 3306);
		break;

	case false:
		define("MysqlURL", "localhost");
		define("MysqlUSER", "root");
		define("MysqlPWD", "");
		define("MysqlDATABASE", "gsb");
		define("MysqlPORT", 3306);
		break;
		

	default:
		echo "<h1>Erreur de config</h1>";
		exit;
		break;
		
}