<?php

$account_number = $_GET['account_number'];
if ( $account_number == "" )
	$account_number = $_POST['account_number'];
if ( $account_number == "" and !isset($_GET['added']))
	header("Location:Liste.php");

$account_name = $_POST['account_name'];
$account_type = $_POST['account_type'];
$bank_account_number = $_POST['bank_account_number'];
$bank_code = $_POST['bank_code'];
$bank_name = $_POST['bank_name'];
$cash_account = $_POST['cash_account'];
$social_worker = $_POST['social_worker'];

// if ( ! ( $user_id == "new" or intval($user_id) or $username != "" ) )
// 	header("Location:Liste.php");

$page_security = '';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");

// $js = "";
// if ($use_popup_windows)
// 	$js .= get_js_open_window(800, 500);
// if ($use_date_picker)
// 	$js .= get_js_date_picker();

if ( $account_number == "new" )
	page($help_context = " ", false, false, "", $js);
else
	page($help_context = " ", false, false, "", $js);

//----------------------------------------------------------------------------------------

if (isset($_GET['added'])) 
{
	display_notification_centered("Das Konto $account_name wurde gespeichert.");
	echo "<br/>
	<center>
		<img src='/accounting/themes/cool/images/button_ok.png'><br/><br/>
	</center>";

   	hyperlink_no_params($_SERVER['PHP_SELF'], "Weiteres Konto bearbeiten");
   	hyperlink_no_params("Liste.php", "Kontoliste");

	display_footer_exit();
}

//----------------------------------------------------------------------------------------

function new_transaction_form()
{
	global $Refs, $account_number, $account_name, $account_type, $bank_account_number, $bank_code, $bank_name, $cash_account, $social_worker;
	
echo "<form method='post' action='Bearbeiten.php' >

<input name='account_number' type=hidden value='$account_number' />

<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner'>
			<tr>
				<td colspan=2 class=tableheader><h3>";
$readonly = "readonly ";
if ( $account_number == "new" ) {
	echo "Neues Klientenkonto anlegen";
	$readonly = "";
	}
else {
	$sql = mysql_fetch_row( mysql_query("SELECT account_type,account_name,bank_account_number,bank_code,bank_name,cash_account,social_worker FROM bank_accounts WHERE account_number='$account_number' LIMIT 1") );
	$account_type = $sql[0];
	$account_name = $sql[1];
	$bank_account_number = $sql[2];
	$bank_code = $sql[3];
	$bank_name = $sql[4];
	$cash_account = $sql[5];
	$social_worker = $sql[6];
	echo "Internes Konto # $account_number: <i>$account_name</i>";
	}
if ( $account_type == "SOZIALARBEITER" ) $soz = " selected";
elseif ( $account_type == "KLIENT" ) $kli = " selected";
elseif ( $account_type == "KASSE" ) $kas = " selected";
elseif ( $account_type == "GELDGEBER" ) $gel = " selected";

echo "</h3></td>
			</tr>
			<tr>
				<td colspan=2>
				&nbsp;<a href='Liste.php'><img src='../themes/default/images/account.png' border=0 />&nbsp;Kontenliste</a><br/>\n";
if ( $account_number != "new" ) echo "\t\t\t\t&nbsp;<a href='Neu.php'><img src='../themes/default/images/new.gif' border=0 />&nbsp;Neues Konto</a><br/>
				&nbsp;<a href='Loeschen.php?account_number=$account_number'><img src='../themes/default/images/delete.gif' border=0 />&nbsp;Dieses Konto l&ouml;schen</a><br/>\n";
echo "				</td>
			</tr>
			<tr>
				<td class='label'>Name:</td>
				<td><input name='account_name' type=text class='textbox' value='$account_name' $readonly/></td>
			</tr>
			<tr>
				<td class='label'>Konto-Typ:</td>
				<td>
					<select name='account_type' class='combo'>
						<option value='SOZIALARBEITER'$soz>Sozialarbeiter</option>
						<option value='KLIENT'$kli>Klient</option>
						<option value='KASSE'$kas>Kasse</option>
						<option value='GELDGEBER'$gel>Geldgeber</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan=2 align=center>Bankverbindung<br/>(nur bei Sozialarbeiter-Konten)</td>
			</tr>
			<tr>
				<td class='label'>Konto-Nr:</td>
				<td><input name='bank_account_number' type=text class='textbox' value='$bank_account_number'></td>
			</tr>
			<tr>
				<td class='label'>BLZ:</td>
				<td><input name='bank_code' type=text class='textbox' value='$bank_code'></td>
			</tr>
			<tr>
				<td class='label'>Kreditinstitut:</td>
				<td><input name='bank_name' type=text class='textbox' value='$bank_name'></td>
			</tr>
			<tr>
				<td colspan=2 align=center>Verbundene Konten</td>
			</tr>
			<tr>
				<td class='label'>Kasse (nur Sozialarbeiter):</td>
				<td>
					<select name='cash_account' class='combo'>
						<option value=''></option>\n";
$sql = mysql_query("SELECT account_number,account_name FROM bank_accounts WHERE account_type='KASSE'");
while ( $r = mysql_fetch_row($sql) ) {
	$selected = "";
	if ( $cash_account == $r[0] ) $selected = " selected";
	echo "\t\t\t\t\t\t<option value='$r[0]'$selected>$r[1]</option>\n";
	}
echo "					</select>
				</td>
			</tr>
			<tr>
				<td class='label'>Zust&auml;ndiger Sozialarbeiter (nur Klienten):</td>
				<td>
					<select name='social_worker' class='combo'>
						<option value=''></option>\n";
$sql = mysql_query("SELECT account_number,account_name FROM bank_accounts WHERE account_type='SOZIALARBEITER'");
while ( $r = mysql_fetch_row($sql) ) {
	$selected = "";
	if ( $social_worker == $r[0] ) $selected = " selected";
	echo "\t\t\t\t\t\t<option value='$r[0]'$selected>$r[1]</option>\n";
	}
echo "					</select>
				</td>
			</tr>
		</table>
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

	if ($_POST['account_name'] == "") {
		display_error("Bitte geben Sie einen Kontonamen an.");
		set_focus('account_name');
		return false;
		}

	if ( intval($_POST['account_name']) ) {
		display_error("Zahlen als Kontonamen sind nicht erlaubt.");
		set_focus('account_name');
		return false;
		}

	if ( $_POST['account_number'] == "new" and mysql_fetch_row( mysql_query("SELECT * FROM bank_accounts where account_name='".$_POST['account_name']."'") ) ) {
		display_error("Ein Konto mit diesem Namen existiert bereits.");
		set_focus('account_name');
		return false;
		}

	return true;
}

//----------------------------------------------------------------------------------------

function add_transaction()
{
	global $account_number, $account_name, $account_type, $bank_account_number, $bank_code, $bank_name, $cash_account, $social_worker;

	if ( $account_number == "new" )
		$sql = mysql_query("INSERT INTO bank_accounts (account_name,account_type,bank_account_number,bank_code,bank_name,cash_account,social_worker) VALUES ('$account_name','$account_type','$bank_account_number','$bank_code','$bank_name','$cash_account','$social_worker')");
	else
		$sql = mysql_query("REPLACE INTO bank_accounts (account_number,account_name,account_type,bank_account_number,bank_code,bank_name,cash_account,social_worker) VALUES ('$account_number','$account_name','$account_type','$bank_account_number','$bank_code','$bank_name','$cash_account','$social_worker')");

	if ( $sql ) {
		meta_forward($_SERVER['PHP_SELF'], "added=yes&account_name=".urlencode($account_name));
		}
	else {
		display_error("Beim Speichern ist ein Fehler aufgetreten:<br/>".mysql_error() );
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
