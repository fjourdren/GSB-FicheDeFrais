<?php
@session_start();

function addFlash($type, $message) {
	$_SESSION['flashs'][] = array('type' => $type, 'message' => $message);
}

function getFlash() {
	return $_SESSION['flashs'];
}

function afficherFlash() {
	
	if(@count($_SESSION['flashs'])!=0) {
		
		echo '<div class="flashs">';
		foreach ($_SESSION['flashs'] as $flash) {
			?>
				<div class="flash <?php echo $flash['type']; ?>">
					<img class="flashCloseButton" src="images/icones/close.png" alt="Masquer"/>
					<h3><?php echo $flash['type']; ?></h3>
					<p><?php echo $flash['message']; ?></p>
				</div>
			<?php
			
		}
		echo '</div>';
		
		unset($_SESSION['flashs']);
		
	}
}
?>