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
page(_($help_context = "Geldeingang verbuchen"), false, false, "", $js);

check_db_has_bank_accounts("Fataler Fehler: Die Datenbank enthält keine Konten");

//----------------------------------------------------------------------------------------

if (isset($_GET['added'])) 
{
	$amount = number_format( str_replace(".", ",", $_GET['amount']), 2, ",", "" );
	$from = urldecode($_GET['from']);
	$to = urldecode($_GET['to']);
	$new_value = number_format( str_replace(".", ",", $_GET['new']), 2, ",", "" );

	display_notification_centered("Die Buchung wurde durchgef&uuml;hrt");
	echo "<br/>
	<center>
		<img src='/accounting/themes/cool/images/button_ok.png'><br/><br/>
		<font size=4>$to hat $amount Euro vom $from erhalten</font>
		<br/><br/>
		<a href='/accounting/Transaktionen/Liste.php'>Neuer Kontostand: &euro; $new_value</a>
	</center>";

   	hyperlink_no_params($_SERVER['PHP_SELF'], "");

	display_footer_exit();
}

if (isset($_POST['_DatePaid_changed'])) {
	$Ajax->activate('_ex_rate');
}

//----------------------------------------------------------------------------------------

function new_transaction_form()
{
	global $Refs;
	
$sql = mysql_fetch_row( mysql_query("SELECT bank_account FROM users WHERE user_id='".$_SESSION['wa_current_user']->user_id."'") );
$myaccount = $sql[0];

echo "<form method='post' action='/accounting/gl/Gutschrift.php' >

<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner'>
			<tr>
				<td colspan=2 class=tableheader><h3>Geldeingang</h3></td>
			</tr>
			<tr>
				<td class='label'>Betrag in &euro;:</td>
				<td><input name=amount class=amount type=text size=15 maxlength=10 dec=2 value='0,00'></td>
			</tr>
			<tr>
				<td colspan=2 align=center><h3>Von Geldgeber</h3></td>
			</tr>
			<tr>
				<td class='label'>Geldgeber:</td>
				<td><span id='_FromBankAccount_sel'>
					<select autocomplete='off'  name='from' class='combo' title=''>\n";
$sql = mysql_query("SELECT account_number,account_name FROM bank_accounts WHERE account_type = 'GELDGEBER';");
while ( $r = mysql_fetch_row($sql) )	# nicht $r[0], denn das wird nicht verrechnet
	echo "\t\t\t\t\t\t<option value='$r[1]'>$r[1]</option>\n";
echo"					</select>
				</span>
				</td>
			</tr>
			<tr>
				<td class='label'>Datum (TT.MM.JJJJ):</td>
				<td><input name=date_from class=textbox size=9 maxlength=12 value='".date("d.m.Y")."' type=text></td>
			</tr>
			<tr>
				<td class='label'>Verwendungszweck:</td>
				<td><textarea name='memo' class=memo cols='40' rows='4'></textarea></td>
			</tr>\n";
if ( $_SESSION['wa_current_user']->user_id == 1 ) { # Admin sieht eine Liste
echo "			<tr>
				<td colspan=2 align=center><h3>An Sozialarbeiter</h3></td>
			</tr>
			<tr>
				<td class='label'>An Sozialarbeiter:</td>
				<td>
					<select autocomplete='off'  name='to' class='combo' title=''>\n";

$current_user = $_SESSION["wa_current_user"]->real_name;
$sql = mysql_query("SELECT account_number,account_name,bank_account_number,bank_code,bank_name FROM bank_accounts WHERE account_type = 'SOZIALARBEITER';");
while ( $r = mysql_fetch_row($sql) ) {
	$selected="";
	if ( $current_user == $r[1] ) $selected = " selected";
	echo "\t\t\t\t\t\t<option value='$r[0]'$selected>$r[1]: $r[2] $r[4]</option>\n";
	}

echo "					</select>
				</td>
			</tr>\n";
	}	# alle anderen Benutzer können nur sich selbst gutschreiben
else	echo "<input type=hidden name=to value=$myaccount />\n";
echo "	</table>
	</td>
	</tr>
</table>
</center>

<br/>

<center>
<button class=ajaxsubmit type=submit aspect='default' name='AddPayment' id='AddPayment' value='AddPayment'>
<img src='../themes/cool/images/ok.gif' height='12'>
<span>Verbuchen</span>
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
	
	if (!is_date($_POST['date_from'])) 
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
	$user = $_SESSION["wa_current_user"]->user_id;
	
	$from = $_POST['from'];
	$d = explode(".", $_POST['date_from']);
	$date_from = $d[2]."-".$d[1]."-".$d[0];

	$to = $_POST['to'];
	
	if ($from == "" or $to == "" or $date_from == "" or $amount == "") {
		display_error("Der Server konnte die eingegebenen Daten nicht lesen.");
		return false;
		}

	if ( mysql_query( "INSERT INTO bank_transactions (user,type,amount,`from`,identifier_from,date_from,`to`,memo) VALUES ( '$user', 'GUTSCHRIFT', '$amount', '$from', 'Banküberweisung', '$date_from', '$to', '$memo' );" ) ) {
		$social = mysql_fetch_row( mysql_query("SELECT account_name FROM `bank_accounts` WHERE account_number='$to';") );
		include_once("/var/www/accounting/includes/account_value.php");
		$social_new = get_current_account_value( $to );
		meta_forward($_SERVER['PHP_SELF'], "added=yes&amount=$amount&from=".urlencode($from)."&to=".urlencode($social[0])."&new=$social_new");
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
