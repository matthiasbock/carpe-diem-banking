<?php

echo "<html>

<head>
<title>Anmeldung fehlgeschlagen</title>
<meta http-equiv='Content-type' content='text/html; charset=iso-8859-1' />
<link href='./themes/default/default.css' rel='stylesheet' type='text/css'> 
</head>

<body id='loginscreen'>
<center>\n";

$title = "Anmeldung";
include("/var/www/frontaccount/includes/header.php");

echo "<div id='_page_body'>
<br/><br/>

<table class='login' cellpadding=2 cellspacing=0 width='100%'>
<tr>
	<td align='center'>
		<center>
		<br/><br/>
		<font size='5' color='red'><b>Anmeldung fehlgeschlagen<b></font>
		<br/><br/>
		<b>Der Benutzer wurde deaktiviert.<b>
		<br/><br/>
		<a href='$path_to_root/index.php'><< Zur&uuml;ck zur Anmeldung</a>
		</center>
	</td>
</tr>
</table>
</div>";
	include("/var/www/frontaccount/includes/footer.php");
	kill_login();

?>