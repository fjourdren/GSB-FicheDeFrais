<?php
/**
 *  Bibliotheque de fonctions AccesDonnees.php
*
*
*
* @author Erwan
* @copyright Estran
* @version 1.2 Jeudi 2 Fevrier 2017
*
* creation de la fonction AfficheRequete
* creation de la focntion nombreChamp
* creation de la fonction typeChamp
* creation de la fonction ligneSQL
*

*/


$modeacces = "mysql";


$mysql_data_type_hash = array(
		1=>'tinyint',
		2=>'smallint',
		3=>'int',
		4=>'float',
		5=>'double',
		7=>'timestamp',
		8=>'bigint',
		9=>'mediumint',
		10=>'date',
		11=>'time',
		12=>'datetime',
		13=>'year',
		16=>'bit',
		//252 is currently mapped to all text and blob types (MySQL 5.0.51a)
		252=>'blob',
		253=>'string',
		254=>'string',
		246=>'decimal'
);






function connexion($host, $port, $dbname, $user, $password) {

	global $modeacces, $connexion;


	if ($modeacces == "mysql") {

		@$link = mysql_connect($host,$user,$password)
		or die("Impossible de se connecter : ".mysql_error());

		@$connexion = mysql_select_db($dbname)
		or die("Impossible d'ouvrir la base : ".mysql_error());

		return $connexion;

	}


	if ($modeacces == "mysqli") {

		$connexion = new mysqli("$host", "$user", "$password", "$dbname", $port);

		if ($connexion->connect_error) {
			die('Erreur de connexion (' . $connexion->connect_errno . ') '. $connexion->connect_error);
		}

		return $connexion;

	}


}



function deconnexion() {

	global $modeacces, $connexion;


	if ($modeacces == "mysql") {

		mysql_close();

	}


	if ($modeacces == "mysqli") {

		$connexion->close();

	}
}



function executeSQL($sql) {

	global $modeacces, $connexion;

	if ($modeacces=="mysql") {
		@$result = mysql_query($sql)
		or die ("Erreur SQL de <b>".$_SERVER["SCRIPT_NAME"]."</b>.<br />
			 Dans le fichier : ".__FILE__." a la ligne : ".__LINE__."<br />".
				mysql_error().
				"<br /><br />
				<b>REQUETE SQL : </b>$sql<br />");
				return $result;
	}

	if ($modeacces=="mysqli") {
		$result = $connexion->query($sql)
		or die ("Erreur SQL de <b>".$_SERVER["SCRIPT_NAME"]."</b>.<br />
			 Dans le fichier : ".__FILE__." a la ligne : ".__LINE__."<br />".
				mysqli_error_list($connexion)[0]['error'].      //$mysqli->error_list;
				"<br /><br />
				<b>REQUETE SQL : </b>$sql<br />");
				return $result;
	}
}



function compteSQL($sql) {

	global $modeacces, $connexion;

	$result = executeSQL($sql);

	if ($modeacces=="mysql") {
		$num_rows = mysql_num_rows($result);
	}

	if ($modeacces=="mysqli") {

		$num_rows = $connexion->affected_rows;
	}

	return $num_rows;

}




function tableSQL($sql) {

	global $modeacces, $connexion;

	$result = executeSQL($sql);
	$rows=array();

	if ($modeacces=="mysql") {
		while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
			array_push($rows,$row);
		}
	}

	if ($modeacces=="mysqli") {
		while ($row = $result->fetch_array(MYSQLI_BOTH)) {
			array_push($rows,$row);
		}
	}

	return $rows;

}



function champSQL($sql) {

	global $modeacces, $connexion;

	$result = executeSQL($sql);

	if ($modeacces=="mysql") {
		$rows = mysql_fetch_array($result, MYSQL_NUM);
	}


	if ($modeacces=="mysqli") {
		$rows = $result->fetch_array(MYSQLI_NUM);

	}

	return $rows[0];
}



function afficheRequete($sql) {

	$results = tableSQL($sql);

	$nbchamps = nombreChamp($sql);

	echo "<table style=\"border: 2px solid blue; border-collapse: collapse; \">";
	echo "   <caption style=\"color:green;font-weight:bold\">$sql</caption>
	<tr style=\"border: 2px solid blue; border-collapse: collapse; \" >";
	for ($i=0;$i<$nbchamps;$i++) {
		echo "<th style=\"border: 2px solid blue; border-collapse: collapse; \">".nomChamp($sql,$i)."(".typeChamp($sql,$i).")</th>";
	}
	echo "   </tr>";

	foreach ($results as $ligne) {
		echo "<tr style=\"border: 1px solid red; border-collapse: collapse; \">";
		//on extrait chaque valeur de la ligne courante
		for ($i=0;$i<$nbchamps;$i++) {
			echo "<td style=\"border: 1px solid red; border-collapse: collapse; \">".$ligne[$i]."</td>";
		}
		echo "</tr>";
	}

	echo "</table>";
}



function nombreChamp($sql) {

	global $modeacces, $connexion;


	$result = executeSQL($sql);

	if ($modeacces=="mysql") {
		return mysql_num_fields($result);
	}

	if ($modeacces=="mysqli") {
		return  $result->field_count;
	}

}



function typeChamp($sql, $field_offset) {

	global $modeacces, $connexion, $mysql_data_type_hash, $typebase;

	$result = executeSQL($sql);

	if ($modeacces=="mysql") {
		return mysql_field_type($result, $field_offset);
	}

	if ($modeacces=="mysqli") {
		return  $mysql_data_type_hash[$result->fetch_field_direct($field_offset)->type];
	}

}



function nomChamp($sql, $field_offset) {

	global $modeacces, $connexion, $mysql_data_type_hash;

	$result = executeSQL($sql);

	if ($modeacces=="mysql") {
		return mysql_field_name($result, $field_offset);
	}

	if ($modeacces=="mysqli") {
		$infos = $result->fetch_field_direct($field_offset);
		return $infos->name;
	}

}



function ligneSQL($sql) {

	global $modeacces, $connexion;

	$result = executeSQL($sql);

	if ($modeacces=="mysql") {
		$rows = mysql_fetch_array($result, MYSQL_NUM);
	}

	if ($modeacces=="mysqli") {
		$rows = $result->fetch_array(MYSQLI_NUM);
	}

	return $rows;
}


?>