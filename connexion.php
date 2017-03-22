<?php
@session_start();

if(@$_SESSION['login'] != "") {
	header('location: index.php');
	exit;
}

include 'layouts/headLogin.inc.php';

?>


<img class="logo" src="images/logo.png" alt="logo" />


<?php

include 'layouts/flash.inc.php';

?>


<div class="login">
  		
  
	 <h2 class="login-header">Authentification</h2>

	 <form class="login-container" method="post" action="connexionAction.php">
		 <p><input type="text" name="pseudo" placeholder="Identifiant"></p>
		 <p><input type="password" name="password" placeholder="Mot de passe"></p>
		 <p><input type="submit" value="Se connecter"></p>
	</form>
		
		
</div>



<?php

	include 'layouts/footerLogin.inc.php';

?>