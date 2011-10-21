<?php

$page_security = '';#'SA_BANKTRANSFER';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/gl/includes/gl_ui.inc");

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(800, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page($help_context = "Bank an Kasse", false, false, "", $js);

check_db_has_bank_accounts("Fataler Fehler: Die Datenbank enthält keine Konten");

//----------------------------------------------------------------------------------------

if (isset($_GET['added'])) 
{
	$amount = $_GET['amount'];
	$from = urldecode($_GET['from']);
	$to = urldecode($_GET['to']);
	$new = str_replace(".", ",", urldecode($_GET['new']) );

	display_notification_centered("Buchung erfolgreich");
	echo "<br/>
	<center>
		<img src='/accounting/themes/cool/images/button_ok.png'><br/><br/>
		<font size=4>
		$amount Euro von $from in $to eingezahlt
		</font><br/><br/>
		Neuer Kontostand<br/><br/>
		$to:&nbsp;&nbsp; EUR $new<br/>
	</center>";

   	hyperlink_no_params($_SERVER['PHP_SELF'], "Weitere Buchung vornehmen");

	display_footer_exit();
}

if (isset($_POST['_DatePaid_changed'])) {
	$Ajax->activate('_ex_rate');
}

//----------------------------------------------------------------------------------------

function new_transaction_form()
{
	global $Refs;
	
echo "<form method='post' action='/accounting/gl/Einzahlung.php' >

<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner'>
			<tr>
				<td colspan=2 class=tableheader><h3>Einzahlung</h3></td>
			</tr>
			<tr>
				<td class='label'>Betrag in &euro;:</td>
				<td><input name=amount class=amount type=text size=15 maxlength=10 dec=2 value='0,00'></td>
			</tr>
			<tr>
				<td colspan=2 align=center><h3>Von Bank</h3></td>
			</tr>
			<tr>
				<td class='label'>Konto:</td>
				<td>
				<select name='from' class='combo' autocomplete='off' title=''>\n";
$result = mysql_query("SELECT account_name,bank_account_number,bank_code,bank_name FROM `bank_accounts` WHERE `account_type` = 'SOZIALARBEITER';");
while ( $r = mysql_fetch_row($result) ) {
	if ( $r[1] != "" )
		echo "\t\t\t\t\t<option value='$r[0]\n$r[1]\n$r[2]\n$r[3]'>$r[0] - $r[1] $r[3]</option>\n";
	}
echo "				</select>
				</td>
			</tr>
			<tr>
				<td class='label'>Datum:</td>
				<td><input name=date_from class=textbox size=9 maxlength=12 value='".date("d.m.Y")."' type=text></td>
			</tr>
			<tr>
				<td class='label'>Buchungskennung:</td>
				<td><input name=identifier_from class=textbox size=9 maxlength=60 value='Barabhebung' type=text></td>
			</tr>
			<tr>
				<td colspan=2 align=center><h3>An Kasse</h3></td>
			</tr>
			<tr>
				<td class='label'>Kasse:</td>
				<td><span id='_ToBankAccount_sel'>
				<select autocomplete='off'  name='to' class='combo' title=''>\n";
$current_users_cash_account = "not found";
$sql = mysql_query("SELECT cash_account FROM bank_accounts WHERE account_name LIKE CONVERT( _utf8 '".$_SESSION["wa_current_user"]->real_name."' USING latin1 );");
if ( $sql ) {
	$r = mysql_fetch_row($sql);
	$current_users_cash_account = intval( $r[0] );
	}
$sql = mysql_query("SELECT account_number,account_name FROM bank_accounts WHERE account_type = 'KASSE';");
while ( $r = mysql_fetch_row($sql) ) {
	$selected = '';
	if ( $r[0] == $current_users_cash_account ) $selected = 'selected';
echo "\t\t\t\t\t<option value='".$r[0]."' $selected>".$r[1]."</option>\n";
	}
echo"				</select>
				</span></td>
			</tr>
			<tr>
				<td class='label'>Buchungskennung / <br/>Quittungsnummer:</td>
				<td><input name=identifier_to class=textbox size=9 maxlength=60 value='Bareinzahlung von ".$_SESSION["wa_current_user"]->real_name." am ".date("d.m.Y")."' type=text></td>
			</tr>
			<tr>
				<td colspan=2 align=center><h3>Verwendungszweck / Notiz</h3></td>
			</tr>
			<tr>
				<td class='label'>Verwendungszweck:</td>
				<td><textarea name='memo' class=memo cols='40' rows='4'></textarea></td>
			</tr>
		</table>
	</td>
	</tr>
</table>
</center>

<br/>

<center>
<button class=ajaxsubmit type=submit aspect='default' name='AddPayment' id='AddPayment' value='AddPayment'>
<img src='../themes/cool/images/ok.gif' height='12'>
<span>Einzahlen</span>
</button>
</center>

</form>
";

}

//----------------------------------------------------------------------------------------

function transaction_is_okay()
{
	global $Refs;

	if (!check_num('amount', 0) or $_POST['amount'] == "" or floatval( str_replace(",", ".", $_POST['amount']) ) == 0) 
	{
		display_error("Der eingegebene Betrag ist ung&uuml;ltig.");
		set_focus('amount');
		return false;
	}
	
	if ($_POST['date_from'] == "" or !is_date($_POST['date_from'])) 
	{
		display_error("Das eingegebene Datum ist ungültig.");
		set_focus('date_from');
		return false;
	}

// 	if ($_POST['date_to'] == "" or !is_date($_POST['date_to']))
// 	{
// 		display_error("Das eingegebene Datum ist ungültig.");
// 		set_focus('date_to');
// 		return false;
// 	}

    return true;
}

//----------------------------------------------------------------------------------------

function add_transaction()
{
	$amount = floatval( str_replace(",", ".", $_POST['amount']) );
	$memo = $_POST['memo'];
	$user = $_SESSION["wa_current_user"]->real_name;
	
	$from = $_POST['from'];
	$identifier_from = $_POST['identifier_from'];
	$d = explode(".", $_POST['date_from']);
	$date_from = $d[2]."-".$d[1]."-".$d[0];

	$to = $_POST['to'];
	$identifier_to = $_POST['identifier_to'];
#	$d = explode(".", $_POST['date_to']);
	$date_to = date("Y-m-d"); #$d[2]."-".$d[1]."-".$d[0];
	
	if ($from == "" or $to == "" or $date_from == "" or $date_to == "" or $amount == "") {
		display_error("Der Server konnte die eingegebenen Daten nicht lesen.");
		return false;
		}

	# Einzahlung speichern
	if ( mysql_query("INSERT INTO bank_transactions (user,amount,type,`from`,identifier_from,date_from,`to`,identifier_to,date_to,memo) VALUES ('$user','$amount','EINZAHLUNG','$from','$identifier_from','$date_from','$to','$identifier_to','$date_to','$memo');") ) {
		$receiver = mysql_fetch_row( mysql_query("SELECT account_name FROM `bank_accounts` WHERE account_number='$to';") );
		# Kassenstand abrufen
		include_once("/var/www/accounting/includes/account_value.php");
		$new_value = get_current_account_value( $to );
		meta_forward($_SERVER['PHP_SELF'], "added=yes&amount=$amount&from=".urlencode($from)."&to=".urlencode(str_replace("\n",",",$receiver[0]))."&new=$new_value");
		}
	else {
		display_error("Die Buchung konnte nicht gespeichert werden:<br/>".mysql_error() );
		return false;
		}
}

//----------------------------------------------------------------------------------------

if (isset($_POST['AddPayment']))
{
	if (transaction_is_okay() == true) 
	{
		add_transaction();
	}
}

new_transaction_form();

end_page();
?>