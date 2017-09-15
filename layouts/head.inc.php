<?php
	@session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
					  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="fr">
	<head>
		<title>GSB Fiche de frais</title>
		<meta charset="ISO-8859-1" />
		<link rel="icon" type="image/png" href="images/logo.png" />
		<link rel="stylesheet" type="text/css" href="styles/default.css" />
		<link rel="stylesheet" type="text/css" href="styles/flashs.css" />

		<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
		<script src="js/flashClick.js"></script>

		<meta name="robots" content="noindex, nofollow" />
		
		<script type="text/javascript">
			var NUMERO_JOUR_DE_CLOTURE = "<?php echo NUMERO_JOUR_DE_CLOTURE; ?>";
		</script>
		
	</head>

	<body>

		<?php
			include 'menu.inc.php';
		?>
		
		<!-- PAGE -->
		<div class="wrap">
			<div class="content">