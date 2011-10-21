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
page(_($help_context = "Überweisung an Kreditor"), false, false, "", $js);

check_db_has_bank_accounts("Fataler Fehler: Die Datenbank enthält keine Konten");

//----------------------------------------------------------------------------------------

if (isset($_GET['added'])) 
{
	$amount = number_format( $_GET['amount'], 2, ",", "" );
	$client = urldecode($_GET['client']);
	$client_new = number_format( str_replace(".", ",", urldecode($_GET['clientnew']) ), 2, ",", "" );

	display_notification_centered("Die Buchung wurde durchgef&uuml;hrt");
	echo "<br/>
	<center>
		<img src='/accounting/themes/cool/images/button_ok.png'><br/><br/>
		<font size=4>
		Im Namen von $client wurden &euro; $amount &uuml;berwiesen
		</font>
		<br/><br/>
		<a href='/accounting/Transaktionen/Liste.php'>
		Neuer Kontostand $client: &euro; $client_new
		</a>
		<br/>
	</center>";

   	hyperlink_no_params($_SERVER['PHP_SELF'], "");

	display_footer_exit();
}

//----------------------------------------------------------------------------------------

function new_transaction_form()
{
global $Refs;

# wie heiße ich ?
$current_username = $_SESSION["wa_current_user"]->real_name;

# welches Konto ist meins ?
$sql = mysql_fetch_row( mysql_query("SELECT bank_account FROM users WHERE user_id='".$_SESSION['wa_current_user']->user_id."'") );
$myaccount = $sql[0];

echo "<form name='myform' method='post' action='/accounting/gl/Kreditor.php' >

<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner'>
			<tr>
				<td colspan=2 class=tableheader><h3>&Uuml;berweisung an Kreditor</h3></td>
			</tr>
			<tr>
				<td class='label'>Betrag in &euro;:</td>
				<td><input name=amount class=amount type=text size=15 maxlength=10 dec=2 value='0,00' /></td>
			</tr>
			<tr>
				<td class='label'>Von Klient:</td>
				<td>	<select name='from' class='combo' title='' autocomplete='off'>\n";

if ( $_SESSION["wa_current_user"]->user_id == 1 )	# Admin sieht eine Liste aller Klienten, Sozialarbeiter nur jeweils ihre Klienten
	$sql = mysql_query("SELECT account_number,account_name,birthday FROM bank_accounts WHERE account_type = 'KLIENT';");
else	$sql = mysql_query("SELECT account_number,account_name,birthday FROM bank_accounts WHERE account_type = 'KLIENT' AND ( social_worker = '$myaccount' OR social_worker = '0' );");

# Klienten aufzählen
while ( list( $account_number, $account_name, $birthday ) = mysql_fetch_row($sql) ) {
	$selected = "";
	if ( $account_name == $current_username )
		$selected = " selected";
	echo "\t\t\t\t\t\t<option value='$account_number'$selected>$account_name";
	if ( $birthday != "" )
		echo ", geb. am $birthday";
	echo "</option>\n";
	}

echo"					</select>
				</td>
			</tr>
			<tr>
				<td class='label' rowspan=2>An Kreditor:</td>
				<td>	<input type=radio name='radio' id='known' value='known'/>Bekannten Kreditor auswählen:<br/>
					<select name='to_select' class='combo' title='' autocomplete='off'>\n";

# Wurden bereits Überweisungen an Kreditoren getätigt ?
$sql = mysql_query("SELECT 'from','to' FROM bank_transactions WHERE type='KREDITOR';");

# Kreditoren aufzählen
if ( $sql )
while ( list( $from, $to ) = mysql_fetch_row($sql) ) {
	# Admin sieht eine Liste aller Kreditoren
	if ( $_SESSION["wa_current_user"]->user_id == 1 )	
		echo "\t\t\t\t\t\t<option value='$to'>$to</option>\n";
	else	{
		# Sozialarbeiter nur Kreditoren seiner Klienten
		$sql2 = mysql_query("SELECT social_worker FROM bank_accounts WHERE account_number='$from' AND account_type='KLIENT' AND ( social_worker='$myaccount' OR social_worker = '0' );");
		if ( $sql2 )
			echo "\t\t\t\t\t\t<option value='$to'>$to</option>\n";
		}	
	}

echo "					</select>
				</td>
			</tr>
			<tr>
				<td valign=top>
					<input type=radio name='radio' id='new' value='new' checked />Neuen Kreditor eingeben:<br/>
					<textarea name='to_textarea' class=memo cols='40' rows='4' onchange=javascript:window.document.myform.to_radio.value='new';></textarea>
				</td>
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
<span>&Uuml;berweisen</span>
</button>
</center>

</form>
";

}

//----------------------------------------------------------------------------------------

function transaction_is_okay()
{
	global $Refs;

	if ($_POST['from'] == "") {
		display_error("Sie müssen einen Klienten auswählen.");
		set_focus('from');
		return false;
		}

	if (!check_num('amount', 0) or $_POST['amount'] == "" or floatval( str_replace(",", ".", $_POST['amount']) ) == 0)
	{
		display_error("Der eingegebene Betrag ist ung&uuml;ltig.");
		set_focus('amount');
		return false;
	}

	$identifier_to = "";
	if ( $_POST['radio'] == "known" )
		$identifier_to = $_POST['to_select'];
	elseif ( $_POST['radio'] == "new" )
		$identifier_to = $_POST['to_textarea'];

	if ( $identifier_to == "" )
	{
		display_error("Bitte w&auml;hlen sie einen Kreditor.");
		set_focus('to_textarea');
		return false;
	}

	return true;
}

//----------------------------------------------------------------------------------------

function add_transaction()
{
	$user = $_SESSION["wa_current_user"]->user_id;
	
	$amount = floatval( str_replace(",", ".", $_POST['amount']) );

	$from = $_POST['from'];
	if ( $_POST['radio'] == "known" )
		$identifier_to = $_POST['to_select'];
	elseif ( $_POST['radio'] == "new" )
		$identifier_to = $_POST['to_textarea'];

#	$date_from = date("Y-m-d");
#	$date_to = $date_from;

	if ( $from == "" or $identifier_to == "" or $amount == "" ) {
		display_error("Der Server konnte die eingegebenen Daten nicht lesen.");
		return false;
		}

	if ( mysql_query( "INSERT INTO bank_transactions (user,type,amount,`from`,`to`,identifier_to) VALUES ('$user','KREDITOR','$amount','$from','Kreditor','$identifier_to');" ) ) {
		list( $client ) = mysql_fetch_row( mysql_query("SELECT account_name FROM `bank_accounts` WHERE account_number='$from';") );
		include_once("/var/www/accounting/includes/account_value.php");
		$client_new = get_current_account_value( $from );
		meta_forward($_SERVER['PHP_SELF'], "added=yes&amount=$amount&client=".urlencode($client)."&clientnew=$client_new");
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
		add_transaction();
}

new_transaction_form();

end_page();
?>
