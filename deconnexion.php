<?php
@session_start();

@session_unset();
@session_destroy();

require_once 'include/flash.lib.php';

addFlash('Succ&#232;s', 'D&#233;connexion r&#233;ussi');
header('location: connexion.php');
exit;
?>