<?php

$user_id = $_GET['user_id'];
if ( $user_id == "" ) $user_id = $_POST['user_id'];

$real_name = $_GET['username'];
if ( $real_name == "" ) $real_name = $_POST['real_name'];
$username = $real_name;

$phone = $_POST['phone'];
$email = $_POST['email'];

if ( ! ( $user_id == "new" or intval($user_id) or $username != "" ) )
	header("Location:Liste.php");

$page_security = '';#'SA_BANKTRANSFER';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(800, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

if ( $user_id == "new" )
	page($help_context = "Neuer Benutzer", false, false, "", $js);
else
	page($help_context = "Benutzer bearbeiten", false, false, "", $js);


if ( $_SESSION['wa_current_user']->user_id != 1 ) {
	echo "<font size=4><b><i>Keine Zugriffserlaubnis. Hierher darf nur der Administrator.</i></b></font>";
	exit;
	}


//----------------------------------------------------------------------------------------

if (isset($_GET['added'])) 
{
	display_notification_centered("Benutzer $username wurde gespeichert.");
	echo "<br/>
	<center>
		<img src='/accounting/themes/cool/images/button_ok.png'><br/><br/>
	</center>";

   	hyperlink_no_params($_SERVER['PHP_SELF'], "Weiteren Benutzer bearbeiten");

	display_footer_exit();
}

//----------------------------------------------------------------------------------------

function new_transaction_form()
{
	global $Refs, $user_id, $real_name, $phone, $email;
	
echo "<form method='post' action='Bearbeiten.php' >

<input name='user_id' type=hidden value='$user_id' />

<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner'>
			<tr>
				<td colspan=2 class=tableheader><h3>";
$readonly = "readonly ";
if ( $user_id == "new" ) {
	echo "Neuer Benutzer";
	$readonly = "";
	}
else {
	echo "Bearbeiten von Benutzer # $user_id";
	$sql = mysql_fetch_row( mysql_query("SELECT password,real_name,phone,email,inactive FROM users WHERE user_id='$user_id'") );
	$password = $sql[0];
	$real_name = $sql[1];
	$phone = $sql[2];
	$email = $sql[3];
	$inactive = $sql[4];
	}
echo "</h3></td>
			</tr>
			<tr>
				<td colspan=2>
				&nbsp;<a href='Liste.php'><img src='../themes/default/images/user.png' border=0 />&nbsp;Benutzerliste</a><br/>\n";
if ( $user_id != "new" ) echo "\t\t\t\t&nbsp;<a href='Neu.php'><img src='../themes/default/images/new.gif' border=0 />&nbsp;Neuer Benutzer</a>\n";
echo "				</td>
			</tr>
			<tr>
				<td class='label'>Name:</td>
				<td><input name='real_name' type=text class='textbox' value='$real_name' $readonly/></td>
			</tr>
			<tr>
				<td class='label'>Telefonnummer:</td>
				<td><input name='phone' type=text class='textbox' value='$phone'></td>
			</tr>
			<tr>
				<td class='label'>Email-Adresse:</td>
				<td><input name='email' type=text class='textbox' value='$email'></td>
			</tr>\n";
if ( isset($inactive) and $inactive == "1" )
	echo "\t\t	<tr>
				<td colspan=2>
					<font color=red>ACHTUNG: Diser Benutzer wurde gelöscht. Durch Klicken auf Speichern wird er reaktiviert.</font>
				</td>
			</tr>\n";
echo "		</table>
	</td>
	</tr>
</table>
</center>

<input name='password' type=hidden value='$password' />

<br/>

<center>
<button class=ajaxsubmit type=submit aspect='default' name='AddUser' id='AddUser' value='AddUser'>
<img src='../themes/cool/images/ok.gif' height='12'>
<span>Speichern</span>
</button>
</center>

</form>
";

}

//----------------------------------------------------------------------------------------

function transaction_is_okay()
{
	global $Refs;

	if ($_POST['real_name'] == "") {
		display_error("Bitte geben Sie einen Benutzernamen an.");
		set_focus('real_name');
		return false;
		}

	if ( intval($_POST['real_name']) ) {
		display_error("Zahlen als Benutzernamen sind nicht erlaubt.");
		set_focus('real_name');
		return false;
		}

	if ( $_POST['user_id'] == "new" and mysql_fetch_row( mysql_query("SELECT * FROM users where real_name='".$_POST['real_name']."'") ) ) {
		display_error("Ein Benutzer mit diesem Namen existiert bereits.");
		set_focus('real_name');
		return false;
		}

	return true;
}

//----------------------------------------------------------------------------------------

function add_transaction()
{
	$user_id = $_POST['user_id'];
	$password = $_POST['password'];
	$real_name = $_POST['real_name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	
	if ($user_id == "" or $real_name == "") {
		display_error("Der Server konnte die eingegebenen Daten nicht lesen.");
		return false;
		}

	if ( $user_id == "new" )
		$sql = mysql_query("INSERT INTO users (real_name,phone,email) VALUES ('$real_name','$phone','$email')");
	else
		$sql = mysql_query("REPLACE INTO users (user_id,password,real_name,phone,email) VALUES ('$user_id','$password','$real_name','$phone','$email')");

	if ( $sql ) {
		$sql = mysql_fetch_row( mysql_query("SELECT user_id,real_name FROM users WHERE real_name='$real_name' AND phone='$phone' AND email='$email'") );
		$user_id = $sql[0];
		$username = $sql[1];
		meta_forward($_SERVER['PHP_SELF'], "added=yes&user_id=$user_id&username=".urlencode($username));
		}
	else {
		display_error("Beim Speichern des Benutzers ist ein Fehler aufgetreten:<br/>".mysql_error() );
		return false;
		}
}

//----------------------------------------------------------------------------------------

if (isset($_POST['AddUser']))
{
	if (transaction_is_okay() == true) 
	{
		add_transaction();
	}
}

new_transaction_form();

end_page();
?>
