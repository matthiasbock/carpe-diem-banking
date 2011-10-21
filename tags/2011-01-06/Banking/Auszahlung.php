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
page(_($help_context = "Kasse an Klient"), false, false, "", $js);

check_db_has_bank_accounts("Fataler Fehler: Die Datenbank enthält keine Konten");

//----------------------------------------------------------------------------------------

if (isset($_GET['added'])) 
{
	$amount = $_GET['amount'];
	$cash = urldecode($_GET['cash']);
	$cash_new = str_replace(".", ",", $_GET['cashnew']);
	$client = urldecode($_GET['client']);
	$client_new = str_replace(".", ",", $_GET['clientnew']);

	display_notification_centered("Die Buchung wurde durchgef&uuml;hrt");
	echo "<br/>
	<center>
		<img src='/accounting/themes/cool/images/button_ok.png'><br/><br/>
		<font size=4>
		$amount Euro aus $cash an $client ausgezahlt
		</font>
		<br/><br/>
		Neue Kontost&auml;nde:
		<br/><br/>
		<table border=0>
			<tr><td>$cash:</td><td>EUR $cash_new</td></tr>
			<tr><td>$client:</td><td>EUR $client_new</td></tr>
		</table>
		<br/>
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
	
echo "<form method='post' action='/accounting/gl/Auszahlung.php' >

<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner'>
			<tr>
				<td colspan=2 class=tableheader><h3>Auszahlung</h3></td>
			</tr>
			<tr>
				<td class='label'>Betrag in &euro;:</td>
				<td><input name=amount class=amount type=text size=15 maxlength=10 dec=2 value='0,00'></td>
			</tr>
			<tr>
				<td class='label'>Datum:</td>
				<td><input name=date_to class=textbox size=9 maxlength=12 value='".date("d.m.Y")."' type=text></td>
			</tr>
			<tr>
				<td colspan=2 align=center><h3>Aus Kasse</h3></td>
			</tr>
			<tr>
				<td class='label'>Kasse:</td>
				<td><span id='_FromBankAccount_sel'>
				<select autocomplete='off'  name='from' class='combo' title=''>\n";
$current_users_cash_account = "not found";
$sql = mysql_query("SELECT cash_account FROM bank_accounts WHERE account_name LIKE CONVERT( _utf8 '".$_SESSION["wa_current_user"]->real_name."' USING latin1 );");
if ( $sql ) {
	$r = mysql_fetch_row($sql);
	$current_users_cash_account = $r[0];
	}
$sql = mysql_query("SELECT account_number,account_name FROM bank_accounts WHERE account_type = 'KASSE';");
while ( $r = mysql_fetch_row($sql) ) {
	$selected = '';
	if ( $r[0] == $current_users_cash_account ) $selected = 'selected';
	echo "\t\t\t\t\t<option value='$r[0]' $selected>$r[1]</option>\n";
	}
echo"				</select>
				</span>
				</td>
			</tr>
			<tr>
				<td colspan=2 align=center><h3>An Klient</h3></td>
			</tr>
			<tr>
				<td class='label'>Klient:</td>
				<td>
					<select autocomplete='off'  name='to' class='combo' title=''>\n";
$sql = mysql_query("SELECT account_number,account_name FROM bank_accounts WHERE account_type = 'KLIENT';");
while ( $r = mysql_fetch_row($sql) )
	echo "\t\t\t\t\t<option value='$r[0]'>$r[1]</option>\n";
echo "					</select>
				</td>
			</tr>
			<tr>
				<td colspan=2 align=center><h3>Verwendungszweck</h3></td>
			</tr>
			<tr>
				<td class='label'>Buchungskennung oder <br/>Quittungsnummer:</td>
				<td><input name=identifier_to class=textbox size=9 maxlength=60 type=text></td>
			</tr>
			<tr>
				<td class='label'>Verwendungszweck oder <br/>Notiz:</td>
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
<span>Auszahlen</span>
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
	
	if (!is_date($_POST['date_to'])) 
	{
		display_error("Das eingegebene Datum ist ungültig.");
		set_focus('date_to');
		return false;
	}

	return true;
}

//----------------------------------------------------------------------------------------

function add_transaction()
{
	$amount = floatval( str_replace(",", ".", $_POST['amount']) );
	$memo = $_POST['memo'];
	$user = $_SESSION["wa_current_user"]->real_name;

	$to = $_POST['to'];
	$identifier_to = $_POST['identifier_to'];
	$d = explode(".", $_POST['date_to']);
	$date_to = $d[2]."-".$d[1]."-".$d[0];

	$from = $_POST['from'];
	$identifier_from = "";
	$date_from = $date_to;
	
	if ($from == "" or $to == "" or $date_to == "" or $amount == "") {
		display_error("Der Server konnte die eingegebenen Daten nicht lesen.");
		return false;
		}

	$cash = mysql_fetch_row( mysql_query("SELECT account_name FROM `bank_accounts` WHERE account_number='$from';") );
	$client = mysql_fetch_row( mysql_query("SELECT account_name FROM `bank_accounts` WHERE account_number='$to';") );

	# Klient an Kasse
	$q1 = "INSERT INTO bank_transactions (user,type,amount,memo,`from`,identifier_from,date_from,`to`,identifier_to,date_to) VALUES ('$user','AUSZAHLUNG','$amount','$memo','$to','Buchung Klient an Kasse','$date_from','$cash[0]','$identifier_to','$date_to');";
	# Kasse an Klient
	$q2 = "INSERT INTO bank_transactions (user,type,amount,memo,`from`,identifier_from,date_from,`to`,identifier_to,date_to) VALUES ('$user','AUSZAHLUNG','$amount','$memo','$from','Barauszahlung','$date_from','$client[0]','$identifier_to','$date_to');";

	$sql1 = mysql_query( $q1 );
	if ( $sql1 )
		$sql2 = mysql_query( $q2 );

	if ( $sql1 and $sql2 ) {
		include_once("/var/www/accounting/includes/account_value.php");
		$cash_new = get_current_account_value( $from );
		$client_new = get_current_account_value( $to );
		meta_forward($_SERVER['PHP_SELF'], "added=yes&amount=$amount&cash=".urlencode($cash[0])."&cashnew=$cash_new&client=".urlencode($client[0])."&clientnew=$client_new");
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
