<?php

$user_id = $_GET['user_id'];
if ( $user_id == "" )
	$user_id = $_POST['user_id'];
$username = $_GET['username'];

if ( $user_id == "" or !intval($user_id) )
	header("Location:Liste.php");

$page_security = '';#'SA_BANKTRANSFER';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(800, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

page($help_context = "Benutzer l&ouml;schen", false, false, "", $js);

//----------------------------------------------------------------------------------------

if (isset($_GET['deleted'])) 
{
	display_notification_centered("Der Benutzer wurde gelöscht.");
	echo "<br/>
	<center>
		<img src='/accounting/themes/cool/images/button_ok.png'><br/><br/>
		<font size=4>
		Benutzer $username wurde gel&ouml;scht !
		</font>
		<br/>
	</center>";

   	hyperlink_no_params($_SERVER['PHP_SELF'], "Weitere Benutzer löschen");

	display_footer_exit();
}

//----------------------------------------------------------------------------------------

function new_transaction_form()
{
	global $Refs, $user_id;

$sql = mysql_fetch_row( mysql_query("SELECT real_name,phone,email FROM users WHERE user_id='$user_id'") );
$real_name = $sql[0];
$phone = $sql[1];
$email = $sql[2];

echo "<form method='post' action='Loeschen.php' >

<input name='user_id' type=hidden value='$user_id' />

<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner' cellpadding=5 cellspacing=3>
			<tr>
				<td colspan=2 class=tableheader><h3>Benutzer wirklich l&ouml;schen ?</h3></td>
			</tr>
			<tr>
				<td class='label'># :</td>
				<td>$user_id</td>
			</tr>
			<tr>
				<td class='label'>Name:</td>
				<td>$real_name</td>
			</tr>
			<tr>
				<td class='label'>Telefonnummer:</td>
				<td>$phone</td>
			</tr>
			<tr>
				<td class='label'>Email-Adresse:</td>
				<td><a href='mailto:$email'>$email</a></td>
			</tr>
		</table>
	</td>
	</tr>
</table>
</center>

<br/>

<center>
<button class=ajaxsubmit type=submit aspect='default' name='Perform' id='Perform' value='Perform'>
<img src='../themes/cool/images/delete.gif' height='12'>
<span>L&ouml;schen</span>
</button>
</center>

</form>
";

}

//----------------------------------------------------------------------------------------

function transaction_is_okay()
{
	global $Refs;

	if ($_POST['user_id'] == "") {	# FATAL
		header("Location:Liste.php");
		return false;
		}

	$sql = mysql_query("SELECT user_id FROM users WHERE inactive='0'");
	if ( !mysql_fetch_row($sql) or !mysql_fetch_row($sql) ) {
		display_error("Löschung verweigert. Es muss mindestens einen aktiven Benutzer geben.");
		return false;
		}

	return true;
}

//----------------------------------------------------------------------------------------

function add_transaction()
{
	$user_id = $_POST['user_id'];
	
	if ($user_id == "") {	# FATAL
		header("Location:Liste.php");
		return false;
		}
	
	$sql = mysql_fetch_row( mysql_query("SELECT real_name,phone,email,last_visit_date FROM users WHERE user_id='$user_id'") );
	$real_name = $sql[0];
	$phone = $sql[1];
	$email = $sql[2];
	$last = $sql[3];

	if ( mysql_query("REPLACE INTO users (user_id,real_name,phone,email,last_visit_date,inactive) VALUES ('$user_id','$real_name','$phone','$email','$last','1')") ) {
		$sql = mysql_fetch_row( mysql_query("SELECT real_name FROM users WHERE user_id='$user_id'") );
		$username = $sql[0];
		meta_forward($_SERVER['PHP_SELF'], "deleted=yes&user_id=$user_id&username=".urlencode($username));
		}
	else {
		display_error("Beim L&ouml;schen des Benutzers ist ein Fehler aufgetreten:<br/>".mysql_error() );
		return false;
		}
}

//----------------------------------------------------------------------------------------

if (isset($_POST['Perform']))
{
	if (transaction_is_okay() == true) 
	{
		add_transaction();
	}
}

new_transaction_form();

end_page();
?>
