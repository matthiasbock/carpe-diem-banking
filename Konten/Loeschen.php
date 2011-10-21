<?php

$account_number = $_GET['account_number'];
if ( $account_number == "" )
	$account_number = $_POST['account_number'];
if ( $account_number == "" and !isset($_POST['deleted']) )
	header("Location:Liste.php");

$page_security = '';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(800, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

page($help_context = "Konto l&ouml;schen", false, false, "", $js);

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

//----------------------------------------------------------------------------------------

function new_transaction_form()
{
	global $Refs, $account_number;

$sql = mysql_fetch_row( mysql_query("SELECT account_name FROM bank_accounts WHERE account_number='$account_number'") );
$account_name = $sql[0];

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
				<td class='label'># :</td>
				<td>$account_number</td>
				<input name='account_number' type=hidden value='$account_number' />
			</tr>
			<tr>
				<td class='label'>Name:</td>
				<td>$account_name<br/>
				<a href='Bearbeiten.php?account_number=$account_number'>
				<img border=0 alt='Bearbeiten' src='../themes/default/images/edit.gif' />&nbsp;</a>
				<a href='../Transaktionen/Liste.php?select=account&account_number=$account_number'>
				<img border=0 alt='Aktivit&auml;t' src='../themes/default/images/list.png' />&nbsp;</a>
				</td>
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

	if ($_POST['account_number'] == "") {	# FATAL
		header("Location:Liste.php");
		return false;
		}

	return true;
}

//----------------------------------------------------------------------------------------

function perform_transaction()
{
	global $account_number;

	if ($account_number == "") {	# FATAL
		header("Location:Liste.php");
		return false;
		}
	
	$sql = mysql_query("DELETE FROM bank_accounts WHERE account_number='$account_number' LIMIT 1");

	if ( $sql ) {
		meta_forward($_SERVER['PHP_SELF'], "deleted=yes");
		}
	else {
		display_error("Beim L&ouml;schen ist ein Fehler aufgetreten:<br/>".mysql_error() );
		return false;
		}
}

//----------------------------------------------------------------------------------------

if (isset($_POST['Perform']))
{
	if (transaction_is_okay() == true) 
	{
		perform_transaction();
	}
}

new_transaction_form();

end_page();
?>
