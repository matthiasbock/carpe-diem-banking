<?php

$account_number = $_GET['account_number'];
if ( $account_number == "" )
	$account_number = $_POST['account_number'];
if ( $account_number == "" and !isset($_POST['deleted']) )
	header("Location:Liste.php");

$page_security = '';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");

page($help_context = "Konto l&ouml;schen", false, false, "", "");

//----------------------------------------------------------------------------------------

if (isset($_GET['deleted'])) 
{
	display_notification_centered("Das Konto wurde gelöscht.");
	echo "<br/>
	<center>
		<img src='/accounting/themes/cool/images/button_ok.png'><br/><br/>
		<br/>
	</center>";

   	hyperlink_no_params($_SERVER['PHP_SELF'], "Weitere Benutzer löschen");

	display_footer_exit();
}


function transaction_is_okay()
{
	global $Refs;

	if ($_POST['account_number'] == "") {	# FATAL
		header("Location:Liste.php");
		return false;
		}

	return true;
}


function perform_transaction()
{
	global $account_number;

	if ($account_number == "") {	# FATAL
		header("Location: Liste.php");
		return false;
		}

	if ($account_number == "1") {	# Admin ist nicht löschbar
		display_error("Der Administrator kann nicht gel&ouml;scht werden.");
		return false;
		}
	
	$sql = mysql_query("DELETE FROM bank_accounts WHERE account_number='$account_number' LIMIT 1;");

	if ( $sql ) {
		meta_forward($_SERVER['PHP_SELF'], "deleted=yes");
		}
	else {
		display_error("Beim L&ouml;schen ist ein Fehler aufgetreten:<br/>".mysql_error() );
		return false;
		}
}


//----------------------------------------------------------------------------------------

if ( isset($_POST['Perform']) and isset($_POST['account_number']) and transaction_is_okay() ) 
		perform_transaction();

//----------------------------------------------------------------------------------------

# Bug:
#list( $myaccount ) = mysql_fetch_row( mysql_query("SELECT bank_account FROM users WHERE user_id='".$_SESSION['current_user']."'") );
#------------

list( $account_name, $birthday, $account_type, $social_worker ) = mysql_fetch_row( mysql_query("SELECT account_name,birthday,account_type,social_worker FROM bank_accounts WHERE account_number='$account_number' LIMIT 1;") );

echo "<form method='post' action='Loeschen.php' >

<input name='account_number' type=hidden value='$account_number' />

<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner' cellpadding=5 cellspacing=3>
			<tr>
				<td colspan=2 class=tableheader><h3>Konto unwiderruflich l&ouml;schen ?</h3></td>
			</tr>
			<tr>
				<td class='label'>Name:</td>
				<td>$account_name";if ( isset($birthday) and $birthday != "" ) echo ", geb. am $birthday"; echo "</td>
			</tr>
		</table>
	</td>
	</tr>
</table>
</center>

<br/>

<center>
";

# Der der angemeldete Benutzer dieses Konto überhaupt löschen ?
$allowed = ( $_SESSION['current_user'] == 1 )						# Admin darf alle löschen
	or ( ( $account_type == "KLIENT" ) and ( $social_worker == $myaccount ) );	# ansonsten nur eigene Klienten löschbar

if ( $allowed )
	echo "<button class=ajaxsubmit type=submit aspect='default' name='Perform' id='Perform' value='Perform'>
<img src='../themes/cool/images/delete.gif' height='12'>
<span>L&ouml;schen</span>
</button>\n";
else	echo "<img src='/accounting/themes/default/images/Locked.png' height=48 />
<font size=4 color=red>&nbsp;&nbsp;Dieses Konto kannst du nicht l&ouml;schen&nbsp;&nbsp;</font>
<img src='/accounting/themes/default/images/Locked.png' height=48 />
<br/><br/>
";


echo "</center>

</form>
";

end_page();
?>
