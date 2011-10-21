<?php
	$path_to_root=".";

	ini_set('xdebug.auto_trace',1);

	include_once("includes/session.inc");

	$app = &$_SESSION["App"];
	if (isset($_GET['application']))
		$app->selected_application = $_GET['application'];
	$app->display();
?>