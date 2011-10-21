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
page(_($help_context = "Zuteilung an Klient"), false, false, "", $js);

check_db_has_bank_accounts("Fataler Fehler: Die Datenbank enthält keine Konten");

//----------------------------------------------------------------------------------------

if (isset($_GET['added'])) 
{
	$amount = number_format( $_GET['amount'], 2, ",", "" );
	$social = urldecode($_GET['social']);
	$client = urldecode($_GET['client']);
	$social_new = number_format( str_replace(".", ",", urldecode($_GET['socialnew']) ), 2, ",", "" );
	$client_new = number_format( str_replace(".", ",", urldecode($_GET['clientnew']) ), 2, ",", "" );

	display_notification_centered("Die Buchung wurde durchgef&uuml;hrt");
	echo "<br/>
	<center>
		<img src='/accounting/themes/cool/images/button_ok.png'><br/><br/>
		<font size=4>
		Sie haben $client $amount Euro zugeteilt
		</font>
		<br/><br/>
		<a href='/accounting/Transaktionen/Liste.php'>
		Neue Kontost&auml;nde:
		<br/>
		<table border=0>
			<tr><td>$social:</td><td>EUR $social_new</td></tr>
			<tr><td>$client:</td><td>EUR $client_new</td></tr>
		</table>
		</a>
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

echo "<form method='post' action='/accounting/gl/Zuteilung.php' >

<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner'>
			<tr>
				<td colspan=2 class=tableheader><h3>Zuteilung</h3></td>
			</tr>
			<tr>
				<td class='label'>Betrag in &euro;:</td>
				<td><input name=amount class=amount type=text size=15 maxlength=10 dec=2 value='0,00'></td>
			</tr>\n";
if ( $_SESSION["wa_current_user"]->user_id == 1 ) {	# Admin sieht eine Liste
echo "			<tr>
				<td class='label'>Von Sozialarbeiter:</td>
				<td><span id='_FromBankAccount_sel'>
					<select name='from' class='combo' title='' autocomplete='off'>\n";
$current_user = $_SESSION["wa_current_user"]->real_name;
$sql = mysql_query("SELECT account_number,account_name FROM bank_accounts WHERE account_type = 'SOZIALARBEITER';");
while ( $r = mysql_fetch_row($sql) ) {
	$selected="";
	if ( $current_user == $r[1] ) $selected = " selected";
	echo "\t\t\t\t\t\t<option value='$r[0]'$selected>$r[1]</option>\n";
	}
echo"					</select>
				</span>
				</td>
			</tr>\n";
	}						# jeder andere kann nur von seinem eigenen Konto aus zuteilen
else 	echo "<input type=hidden name=from value=$myaccount />\n";

echo "			<tr>
				<td class='label'>Aus Geldeingang:</td>
				<td>
					<select name='identifier_from' class='combo' title='' autocomplete='off'>\n";
if ( $_SESSION["wa_current_user"]->user_id == 1 ) {	# Admin sieht alle
	$to_name = mysql_fetch_row( mysql_query("SELECT account_name FROM bank_accounts WHERE account_number=$myaccount;") );
	$sql = mysql_query("SELECT transaction_number,amount,`from`,date_from,`to` FROM bank_transactions WHERE `type`='GUTSCHRIFT' ORDER BY timestamp DESC;");
	while ( $r = mysql_fetch_row($sql) ) {
		$d = explode("-", $r[3]);
		echo "\t\t\t\t\t\t<option value='$r[0]'>$r[1] Euro vom $r[2] an $r[4] am $d[2].$d[1].$d[0]</option>\n";
		}
	}
else {		# jeder andere nur seine eigenen Gutschriften
	$to_name = mysql_fetch_row( mysql_query("SELECT account_name FROM bank_accounts WHERE account_number=$myaccount;") );
	$sql = mysql_query("SELECT transaction_number,amount,`from`,date_from,`to` FROM bank_transactions WHERE `type`='GUTSCHRIFT' AND `to`='$myaccount' ORDER BY timestamp DESC;");
	while ( $r = mysql_fetch_row($sql) ) {
		$d = explode("-", $r[3]);
		echo "\t\t\t\t\t\t<option value='$r[0]'>$r[1] Euro vom $r[2] am $d[2].$d[1].$d[0]</option>\n";
		}
	}
echo "					</select>
				</td>
			</tr>
			<tr>
				<td class='label'>An Klient:</td>
				<td>
					<select autocomplete='off' name='to' class='combo' title=''>\n";
if ( $_SESSION["wa_current_user"]->user_id == 1 ) {	# Admin sieht alle
	$sql = mysql_query("SELECT account_number,account_name FROM bank_accounts WHERE account_type='KLIENT';");
	while ( $r = mysql_fetch_row($sql) )
		echo "\t\t\t\t\t\t<option value='$r[0]'>$r[1]</option>\n";
	}
else {	# jeder andere nur seine eigenen Klienten
	$sql = mysql_query("SELECT account_number,account_name FROM bank_accounts WHERE account_type='KLIENT' AND (social_worker='0' OR social_worker='$myaccount');");
	while ( $r = mysql_fetch_row($sql) )
		echo "\t\t\t\t\t\t<option value='$r[0]'>$r[1]</option>\n";
	}
echo "					</select>
				</td>
			</tr>
			<tr>
				<td class='label'>Verwendungszweck:<br/><small>(optional)</small></td>
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
<span>Zuteilen</span>
</button>
</center>

</form>
";

}

//----------------------------------------------------------------------------------------

function transaction_is_okay()
{
	global $Refs;

	if ($_POST['to'] == "") {
		display_error("Sie müssen einen Klienten als Empfänger auswählen.");
		set_focus('to');
		return false;
		}

	if ($_POST['identifier_from'] == "") {
		display_error("Sie müssen eine Gutschrift auswählen, aus der zugeteilt werden soll.");
		set_focus('identifier_from');
		return false;
		}

	if (!check_num('amount', 0) or $_POST['amount'] == "" or floatval( str_replace(",", ".", $_POST['amount']) ) == 0)
	{
		display_error("Der eingegebene Betrag ist ung&uuml;ltig.");
		set_focus('amount');
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
	$identifier_from = $_POST['identifier_from'];

	$to = $_POST['to'];
	
	if ($from == "" or $identifier_from == "" or $to == "" or $amount == "") {
		display_error("Der Server konnte die eingegebenen Daten nicht lesen.");
		return false;
		}

	if ( mysql_query( "INSERT INTO bank_transactions (user,type,amount,`from`,identifier_from,`to`,memo) VALUES ( '$user', 'ZUTEILUNG', '$amount', '$from', '$identifier_from', '$to', '$memo' );" ) ) {
		$social = mysql_fetch_row( mysql_query("SELECT account_name FROM `bank_accounts` WHERE account_number='$from';") );
		$client = mysql_fetch_row( mysql_query("SELECT account_name FROM `bank_accounts` WHERE account_number='$to';") );
		include_once("/var/www/accounting/includes/account_value.php");
		$social_new = get_current_account_value( $from );
		$client_new = get_current_account_value( $to );
		meta_forward($_SERVER['PHP_SELF'], "added=yes&amount=$amount&social=".urlencode($social[0])."&socialnew=$social_new&client=".urlencode($client[0])."&clientnew=$client_new");
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
