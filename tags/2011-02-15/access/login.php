<?php

if (!isset($path_to_root) || isset($_GET['path_to_root']) || isset($_POST['path_to_root'])) { # direkter Aufruf der Seite ist verboten
#	header("Location:../index.php");
	}

#include_once("../includes/ui.inc");
	
if (!isset($def_coy))
	$def_coy = 0;
$def_theme = "default";

$title = "Klientenkonten Carpe Diem e.V. - Anmeldung";
$encoding = "iso-8859-15";

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
<html>
<head><title>$title</title>
<meta http-equiv='Content-type' content='text/html; charset=$encoding' />
<link href='$path_to_root/themes/$def_theme/default.css' rel='stylesheet' type='text/css'>\n";

echo "</head>

<body id='loginscreen'>\n";

$title = "Anmeldung";
include("/var/www/frontaccount/includes/header.php");

echo "<div id='_page_body'>

<br/><br/>

<form method='post' action='/accounting/index.php' name='loginform'>
<center>
<table class='login' cellpadding=2 cellspacing=0>
	<tr>
		<td align='center' colspan=2>
			<img src='./themes/default/images/logo_frontaccounting.png' alt='Carpe Diem e.V.' border='0' />
		</td>
	</tr>

	<tr>
		<td colspan=2 class='tableheader'>Bitte melden Sie sich an</td>
	</tr>

	<tr>
		<td class='label'>&nbsp;&nbsp;Benutzername:</td>
		<td align=center><select name='user_name_entry_field' size=1 width=27 style='width:200px'>\n";

include_once("/var/www/frontaccount/config_db.php");
$db = mysql_connect($conn["host"] , $conn["dbuser"], $conn["dbpassword"]);
mysql_select_db($conn["dbname"], $db);

$query = mysql_query("SELECT user_id,real_name FROM `users` WHERE inactive='0'");

while ( $result = mysql_fetch_row( $query ) )
echo "			<option value='$result[0]'>$result[1]</option>\n";

echo "		</select></td>
	</tr>

	<tr>
		<td class='label'>&nbsp;&nbsp;Passwort:</td>
		<td align=center><input type=password name='password' maxlength=20 style='width:195px' /></td>
	</tr>

	<tr>
		<td colspan=2 align=center>
			<input type='submit' value='&nbsp;&nbsp;Anmelden&nbsp;&nbsp;' name='SubmitUser' />
		</td>
	</tr>
</table>
</form>

<script language='JavaScript' type='text/javascript'>
            document.forms[0].password.select();
            document.forms[0].password.focus();
    </script>
</div>";

include_once("/var/www/frontaccount/includes/footer.php");

?>
