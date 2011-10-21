<?php
	if ( stripos( $_SERVER['REQUEST_URI'], "index.php?application=accounts" ) !== FALSE )
		header("Location: /accounting/Konten/Liste.php");

	elseif ( stripos( $_SERVER['REQUEST_URI'], "index.php?application=transactions" ) !== FALSE )
		header("Location: /accounting/Transaktionen/Liste.php");

	elseif ( stripos( $_SERVER['REQUEST_URI'], "index.php?application=users" ) !== FALSE )
		header("Location: /accounting/Benutzer/Liste.php");

	$path_to_root=".";

	ini_set('xdebug.auto_trace',1);

	include_once("includes/session.inc");

	$app = &$_SESSION["App"];
	if (isset($_GET['application']))
		$app->selected_application = $_GET['application'];
	$app->display();
?>
