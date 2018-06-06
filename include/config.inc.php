<?php
define('ADMINNAME', 'admin');
define('COMPTANAME', 'comptable');

define('NUMERO_JOUR_DE_CLOTURE', 20);

define('TEMPS_VALIDITE_PASSWORD', 30);

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

	case false:	//config cli
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