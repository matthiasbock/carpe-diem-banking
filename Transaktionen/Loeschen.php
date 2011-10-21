<?php

$transaction_number = $_GET['transaction_number'];
if ( $transaction_number == "" )
	$transaction_number = $_POST['transaction_number'];
if ( $transaction_number == "" and !isset($_GET['deleted']) )
	header("Location:Liste.php");

$page_security = '';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(800, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

page($help_context = "Transaktion l&ouml;schen", false, false, "", $js);

//----------------------------------------------------------------------------------------

if (isset($_GET['deleted'])) 
{
	display_notification_centered("Die Transaktion wurde gelöscht.");
	echo "<br/>
	<center>
		<img src='/accounting/themes/cool/images/button_ok.png'><br/><br/>
		<br/>
	</center>";

   	hyperlink_no_params("Liste.php", "Weitere Transaktionen löschen");
   	hyperlink_no_params("Liste.php", "Transaktionsliste");

	display_footer_exit();
}

//----------------------------------------------------------------------------------------

function new_transaction_form()
{
	global $Refs, $transaction_number;

$sql = mysql_fetch_row( mysql_query("SELECT type,amount,`from`,`to`,timestamp FROM bank_transactions WHERE transaction_number='$transaction_number'") );
$type = $sql[0];
$amount = $sql[1];
$from = $sql[2];
if ( intval($from) ) {
	$sql2 = mysql_fetch_row( mysql_query("SELECT account_name FROM bank_accounts WHERE account_number='$from' LIMIT 1") ); 
	$from = $sql2[0];
	}
$to = $sql[3];
if ( intval($to) ) {
	$sql2 = mysql_fetch_row( mysql_query("SELECT account_name FROM bank_accounts WHERE account_number='$to' LIMIT 1") ); 
	$to = $sql2[0];
	}
$t = split( "-", str_replace(" ", "-", $sql[4]) );
$timestamp = $t[2].".".$t[1].".".$t[0]." ".$t[3];

echo "<form method='post' action='Loeschen.php' >

<input name='transaction_number' type=hidden value='$transaction_number' />

<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner' cellpadding=5 cellspacing=3>
			<tr>
				<td colspan=2 class=tableheader><h3>Transaktion unwiderruflich l&ouml;schen ?</h3></td>
			</tr>
			<tr>
				<td class='label'>Details:</td>
				<td>
				$type &uuml;ber &euro; $amount
				</td>
			</tr>
			<tr>
				<td class='label'>Von:</td>
				<td>$from</td>
			</tr>
			<tr>
				<td class='label'>An:</td>
				<td>$to</td>
			</tr>
			<tr>
				<td class='label'>Datum:</td>
				<td>$timestamp</td>
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

	if ($_POST['transaction_number'] == "") {	# FATAL
		header("Location:Liste.php");
		return false;
		}

	return true;
}

//----------------------------------------------------------------------------------------

function perform_transaction()
{
	global $transaction_number;

	if ($transaction_number == "") {	# FATAL
		header("Location:Liste.php");
		return false;
		}
	
	$sql = mysql_query("DELETE FROM bank_transactions WHERE transaction_number='$transaction_number' LIMIT 1");

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
