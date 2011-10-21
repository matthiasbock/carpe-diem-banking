<?php

$select = $_GET['select'];
$user_id = $_GET['user_id'];
$username = urldecode($_GET['username']);
$account_number = $_GET['account_number'];
$account_name = urldecode($_GET['account_name']);
$after = urldecode($_GET['after']);
$d = split( "\.", $after );
if ( !intval($d[0]) or !intval($d[1]) or !intval($d[2]) ) $after = "";
$transaction_numbers = urldecode($_GET['transaction_numbers']);
$select_date = $_GET['select_date'];
$select_type = $_GET['select_type'];
$select_amount = $_GET['select_amount'];

$page_security = '';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(800, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

page($help_context = "Transaktionsliste", false, false, "", $js);

echo "<b><font size=5>Achtung: Diese Seite wird gerade &uuml;berarbeitet</font></b>";

if ( $select == "user" ) {
	if ( $user_id == "") {
		$sql = mysql_fetch_row( mysql_query("SELECT user_id FROM users WHERE real_name = '$username';") );
		if ( $sql ) $user_id = $sql[0];
		}
	elseif ( $username == "" ) {
		$sql = mysql_fetch_row( mysql_query("SELECT real_name FROM users WHERE user_id = '$user_id';") );
		if ( $sql ) $username = $sql[0];
		}
	}
elseif ( $select == "account" ) {
	if ( $account_number == "") {
		$sql = mysql_fetch_row( mysql_query("SELECT account_number FROM bank_accounts WHERE account_name = '$account_name';") );
		if ( $sql ) $account_number = $sql[0];
		}
	elseif ( $account_name == "" ) {
		$sql = mysql_fetch_row( mysql_query("SELECT account_name FROM bank_accounts WHERE account_number = '$account_number';") );
		if ( $sql ) $account_name = $sql[0];
		}
	}

echo "
<form method='GET' action='Liste.php'>
<input type=hidden name='select' value='account' />
<input type=hidden name='account_number' value='$account_number' />
<input type=hidden name='account_name' value='$account_name' />
<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner' cellspacing=5 cellpadding=5>
			<tr>
				<td colspan=15 class=tableheader>
				<h3>";
if ( $select == "" )
	echo "Liste aller gespeicherten Transaktionen";
elseif ( $select == "user" )
	echo "Transaktionsliste des Benutzers '$username'";
elseif ( $select == "account" ) {
	echo "Transaktionsliste f&uuml;r das Konto '<a href='../Konten/Bearbeiten.php?account_number=$account_number&account_name=".urlencode($account_name)."'>$account_name</a>'";
	if ( $after != "" )
		echo " ab dem $after";
	}
elseif ( $select == "transaction" )
	echo "Transaktionen nach Nummer<br/># $transaction_numbers";
elseif ( $select == "date" ) {
	$d = split( "-", $select_date );
	$date = $d[2].".".$d[1].".".$d[0];
	echo "Transaktionsliste f&uuml;r das Datum $date";
	}
elseif ( $select == "type" )
	echo "Alle Transaktionen vom Typ $select_type";
elseif ( $select == "amount" )
	echo "Alle Transaktionen &uuml;ber einen Betrag von &euro; $select_amount";
else
	echo "Es ist ein Fehler aufgetreten";
			echo	"</h3></td>
			</tr>
			<tr>\n";
if ( $select == "account" ) {
	include_once("/var/www/accounting/includes/account_value.php");
	$state = get_current_account_value( $account_number );
	if ( $state >= 0 )
		$state = "<font size=4><b>+ ".$state." &euro;</b></font>";
	else
		$state = "<font size=4 color=red><b>- ".-$state." &euro;</b></font>";
	echo "\t			<td colspan=4>
				<a href='../index.php?application=banking'>
				<img border=0 src='../themes/default/images/new.gif'>
				Neue Transaktion</a>
				</td>\n";
	if ( $after == "" ) {
		$day = date("d");
		$year = date("Y");
		$month = intval( date("m") )-1;
		if ( $month == 0 ) {
			$month = 12;
			$year = $year-1;
			}
		echo "\t\t	<td colspan=2>Ab&nbsp;<input type=text name=after length=10 maxlength=10 value='$day.$month.$year' /></td>\n";
		}
	else
		echo "\t\t	<td colspan=2>Ab&nbsp;<input type=text name=after length=10 maxlength=10 value='$after' /></td>\n";
	echo "\t			<td colspan=2><input type=submit value='>> Eingrenzen' /></a></td>
				<td></td>
				<td colspan=3 class=tableheader>Aktueller Kontostand:</td>
				<td colspan=2 class=tableheader align=right>$state</td>\n";
	}
else
	echo "\t			<td colspan=14>
				<a href='../index.php?application=banking'>
				<img border=0 src='../themes/default/images/new.gif'>
				Neue Transaktion</a>
				</td>\n";
echo "			</tr>
			<tr>
				<td></td>
				<td class=tableheader>#</td>
				<td class=tableheader>Datum</td>
				<td class=tableheader>Benutzer</td>
				<td class=tableheader>Art</td>
				<td class=tableheader>Betrag</td>
				<td class=tableheader>Von</td>
				<td class=tableheader>&nbsp;</td>
				<td class=tableheader>Datum</td>
				<td class=tableheader>An</td>
				<td class=tableheader>&nbsp;</td>
				<td class=tableheader>Datum</td>
				<td class=tableheader>Verwendungszweck</td>
				<td class=tableheader>&nbsp;</td>
			</tr>\n";
if ( $select == "" )
	$where = "";
elseif ( $select == "user" )
	$where = "WHERE user = '$username'";
elseif ( $select == "account" )
	$where = "WHERE `from` = '$account_name' OR `to` = '$account_name' OR `from` = '$account_number' OR `to` = '$account_number'";
elseif ( $select == "transaction" )
	$where = "WHERE transaction_number = '".str_replace(",", "' OR transaction_number = '", $transaction_numbers)."'";
elseif ( $select == "date" )
	$where = "WHERE date_from='$select_date' or date_to='$select_date'";
elseif ( $select == "type" )
	$where = "WHERE type='$select_type'";
elseif ( $select == "amount" )
	$where = "WHERE amount='$select_amount'";

$sql = mysql_query("SELECT transaction_number,timestamp,user,type,amount,`from`,identifier_from,date_from,`to`,identifier_to,date_to,memo FROM bank_transactions $where ORDER BY timestamp DESC, transaction_number DESC;");
$style = "evenrow";
while ( $r = mysql_fetch_row($sql) ) {
	$visible = True;
	if ( $after != "" ) {
		$d = split( "\.", $after );
		$query = "SELECT date(`timestamp`) FROM `bank_transactions` WHERE transaction_number='$r[0]' AND `timestamp` >= '$d[2]-$d[1]-$d[0]' LIMIT 1";
		if ( !mysql_fetch_row( mysql_query($query) ) ) $visible = False;
		}
	if ( $visible ) {
		echo "\t\t\t<tr class=$style>\n";
		echo "\t\t\t\t<td><img border=0 alt='Transaktion' src='../themes/default/images/money.png' /></td>\n";
		if ( $style == "evenrow" )
			$style = "oddrow";
		else	$style = "evenrow";
		}
	for ( $i = 0; $i < 12; $i++ ) {
		$value = $r[$i];
		if ( $i == 1 ) {	# timestamp
			$v = split("-", str_replace(" ", "-", $value) );
			$value = $v[2].".".$v[1].".".$v[0]." ".$v[3];
			}
		elseif ( $i == 2 ) { # Benutzer
			$sql2 = mysql_fetch_row( mysql_query("SELECT user_id,real_name FROM users WHERE user_id='$value' OR real_name='$value' LIMIT 1") );
			$nr = $sql2[0];
			$name = $sql2[1];
			$value = $name."<br/>
				<a href='../Benutzer/Bearbeiten.php?user_id=$nr'><img border=0 alt='Bearbeiten' src='../themes/default/images/user.png' /></a>
				<a href='Liste.php?select=user&username=".urlencode($name)."'><img border=0 alt='Aktivit&auml;t' src='../themes/default/images/list.png' width=22 /></a>";
			}
		elseif ( $i == 3 ) { # Typ
			$value = "<a href='Liste.php?select=type&select_type=$value'>$value</a>";
			}
		elseif ( $i == 4 ) { # Betrag
			$a = $value;
			if ( $select == "account" ) {
				$positive = True;
				$neutral = True;
				if ( intval($r[5]) and intval($r[5]) == $account_number ) {
					$positive = False;
					$neutral = False;
					}
				elseif ( intval($r[8]) and intval($r[8]) == $account_number ) {
					#$positive = True;
					$neutral = False;
					}
				if ( $neutral )
					$value = "( ".$value." &euro; )";
				elseif ( $positive )
					$value = "+ ".$value." &euro;";
				else	# negative
					$value = "<font color=red>- $value &euro;</font>";
				}
			else	$value = $value." &euro;";
			$value = "<a href='Liste.php?select=amount&select_amount=$a'>".$value."</a>";
			}
		elseif ( $i == 5 or $i == 8 ) { # Von, An
			if ( $r[3] != "EINZAHLUNG" or $i != 5 ) {
				$sql2 = mysql_fetch_row( mysql_query("SELECT account_number,account_name FROM bank_accounts WHERE account_number = '$value' OR account_name = '$value' LIMIT 1") );
				$nr = $sql2[0];
				$name = $sql2[1];
				$value = "$name<br/>
<a href='../Konten/Bearbeiten.php?account_number=$nr&account_name=".urlencode($name)."'><img border=0 alt='Bearbeiten' src='../themes/default/images/edit.gif' />&nbsp;</a>
<a href='Liste.php?select=account&account_number=$nr&account_name=".urlencode($name)."'><img border=0 alt='Aktivit&auml;t' src='../themes/default/images/list.png' width=22 />&nbsp;</a>";
				}
			}
		elseif ( $i == 6 or $i == 9 ) { # Buchungskennung 1 & 2
			if ( intval($value) ) {
				$d = mysql_fetch_row( mysql_query("SELECT date_from FROM bank_transactions WHERE transaction_number='$value'") );
				$d = split( "-", $d[0] );
				$date = $d[2].".".$d[1].".".$d[0];
				$value = "<a href='Liste.php?select=transaction&transaction_numbers=$value'><img border=0 alt='Transaktion' src='../themes/default/images/money.png' />&nbsp;#&nbsp;$value<br/>$date</a>";
				}
			elseif ( $i == 9 and $r[3] == "GUTSCHRIFT" ) { # type
				# ZUTEILUNGEN auflisten
				$value = "";
				$x = floatval($r[4]);
				$sql2 = mysql_query("SELECT transaction_number,`to`,amount FROM bank_transactions WHERE type='ZUTEILUNG' AND identifier_from='$r[0]'");
				while ( $s = mysql_fetch_row($sql2) ) {
					$receiver = $s[1];
					$a = $s[2]; $x = $x-floatval($a);
					if ( intval($receiver) ) {
						$sql3 = mysql_fetch_row( mysql_query("SELECT account_name FROM bank_accounts WHERE account_number='$receiver' LIMIT 1") );
						$receiver = $sql3[0];
						}
					$value .= "<a href='Liste.php?select=transaction&transaction_numbers=$s[0]'><img border=0 alt='Transaktion' src='../themes/default/images/money.png' />&nbsp;# $s[0]: <font color=red>- $a &euro;</font><br/>$receiver</a><br/>\n";
					}
				$value .= "<hr>Verbleibend: $x &euro;";
				}
			}
		elseif ( $i == 7 or $i == 10 ) { # Datum Von, An
			if ( $value != "" ) {
				$d = split("-", $value);
				$date = $d[2].".".$d[1].".".$d[0];
				$value = "<a href='Liste.php?select=date&select_date=$value'>$date</a>";
				}
			}
		if ( $visible )
			echo "\t\t\t\t<td align=center>$value</td>\n";
		}
	if ( $visible ) {
		echo "\t\t\t\t<td>
			<a href='Loeschen.php?transaction_number=$r[0]'><img border=0 src='../themes/default/images/delete.gif'></a>
			</td>\n";
		echo "\t\t\t</tr>\n";
		}
	}
echo "					</select>
		</table>
	</td>
	</tr>
</table>
</form>
</center>

<br/>

</center>
";

end_page();
?>
