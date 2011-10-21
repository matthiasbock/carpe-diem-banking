<?php

if ( $_SESSION['wa_current_user']->user_id == 1 )
	header("Location: Admin-Liste.php");

$page_security = '';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");

# -----------------------------------------------------------------------------------

list( $myaccount ) = mysql_fetch_row( mysql_query("SELECT bank_account FROM users WHERE user_id='".$_SESSION['wa_current_user']->user_id."' LIMIT 1;") );

list( $myaccountname ) = mysql_fetch_row( mysql_query("SELECT account_name FROM bank_accounts WHERE account_number='$myaccount' LIMIT 1;") );

$account_number = $_GET['account_number'];

if ( $account_number == "" )
	$account_number = $myaccount;

list( $account_name ) = mysql_fetch_row( mysql_query("SELECT account_name FROM bank_accounts WHERE account_number='$account_number';") );

#$after = urldecode($_GET['after']);
#$d = split( "\.", $after );
#if ( !intval($d[0]) or !intval($d[1]) or !intval($d[2]) ) $after = "";

# -----------------------------------------------------------------------------------

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(800, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

page($help_context = "Umsatz", false, false, "", $js);


echo "
<form method='GET' action='Liste.php'>
<input type=hidden name='account_number' value='$account_number' />
<input type=hidden name='account_name' value='$account_name' />
<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner' cellspacing=5 cellpadding=5>
			<tr>
				<td colspan=15 class=tableheader>
				<h3>";
if ( $account_number == $myaccount )
	echo "Meine Kontoums&auml;tze";
else	echo "Kontoums&auml;tze f&uuml;r das Konto $account_name";

include_once("/var/www/accounting/includes/account_value.php");				# Kontostand
$b = get_current_account_value( $account_number );
if ( $b < 0 )
	$balance = "<font color=red>&euro; ".number_format( $b, 2, ",", "" )."</font>";
else	$balance = "&euro; ".number_format( $b, 2, ",", "" );

echo				"</h3></td>
			</tr>
			<tr>
				<td colspan=4>&nbsp;</td>
				<td colspan=2 align=right>
					<b>$balance</b>
				</td>
			</tr>
			<tr>
				<td colspan=7>	<hr/>
				</td>
			<tr>
				<td class=tableheader colspan=2>Vorgang</td>
				<td class=tableheader>Von</td>
				<td class=tableheader>An</td>
				<td class=tableheader>Verwendungszweck</td>
				<td class=tableheader>Betrag</td>
				<td class=tableheader>&nbsp;</td>
			</tr>
			</tr>
";

$sql = mysql_query("SELECT transaction_number,timestamp,user,type,amount,`from`,identifier_from,date_from,`to`,identifier_to,date_to,memo FROM bank_transactions ORDER BY timestamp DESC, transaction_number DESC;");
$a = $account_number;
while ( list(	$transaction_number,
		$timestamp,
		$user,
		$type,
		$amount,
		$from,
		$identifier_from,
		$date_from,
		$to,
		$identifier_to,
		$date_to,
		$memo			 ) = mysql_fetch_row($sql) ) {
	list( $social_worker_from )	= mysql_fetch_row( mysql_query("SELECT social_worker FROM bank_accounts WHERE account_number = '$from' LIMIT 1;") );
	list( $social_worker_to )	= mysql_fetch_row( mysql_query("SELECT social_worker FROM bank_accounts WHERE account_number = '$to' LIMIT 1;") );
	$visible = ( ( ( $a == $myaccount ) and ( $user == $_SESSION['wa_current_user']->user_id or $from == $myaccount or $to == $myaccount or $social_worker_from == $myaccount or $social_worker_to == $myaccount ) ) or ( ( $a != $myaccount ) and ( $from == $a or $to == $a ) ) );
	if ( $visible ) {
		if ( $style == "oddrow" )
			$style = "evenrow";
		else	$style = "oddrow";
		echo "<tr class=$style>\n";

		$v = split("-", str_replace(" ", "-", $timestamp) );
		$timestamp = $v[2].".".$v[1].".".$v[0]." ".$v[3]." Uhr";

		list( $username ) = mysql_fetch_row( mysql_query("SELECT real_name FROM users WHERE user_id = '$user';") );

		echo	"<td>$username<br/><small>$timestamp</small></td>\n";

		echo	"<td>$type</td>\n";

		if ( intval( $from ) )
			list( $from ) = mysql_fetch_row( mysql_query("SELECT account_name FROM bank_accounts WHERE account_number = '$from';") );
		echo	"<td>$from<br/><small>";
		if ( $type == "ZUTEILUNG" and intval( $identifier_from ) ) {
			}
		elseif ( $identifier_from != "" )
			echo	"$identifier_from<br/>";
		if ( $date_from != "0000-00-00" ) {
			$d = split("-", $date_from);
			$date_from = $d[2].".".$d[1].".".$d[0];
			echo	"$date_from<br/>";
			}
		echo	"</small></td>\n";

		if ( intval( $to ) )
			list( $to ) = mysql_fetch_row( mysql_query("SELECT account_name FROM bank_accounts WHERE account_number = '$to';") );
		echo	"<td>$to<br/><small>";
		if ( $identifier_to != "" ) 
			echo	"$identifier_to<br/>";
		if ( $date_to != "0000-00-00" ) {
			$d = split("-", $date_to);
			$date_to = $d[2].".".$d[1].".".$d[0];
			echo	"$date_to<br/>";
			}
		echo	"</small></td>\n";

		echo	"<td>$memo</td>\n";

		$remaining = $amount;
		if ( $amount < 0 )
			echo "<td align=right>	<font color=red>";
		else	echo "<td align=right>	<font>";
		$amount = number_format( $amount, 2, ",", "" );
		echo	"&euro; $amount</font><br/>";
		
		if ( $type == "GUTSCHRIFT" ) {	# ist davon schon etwas abgebucht worden ?
			$sql2 = mysql_query("SELECT transaction_number,`to`,amount,timestamp FROM bank_transactions WHERE type='ZUTEILUNG' AND identifier_from='$transaction_number'");
			if ( $sql2 ) {
				echo	"<table border=0>\n";
				while ( list( $_transaction_number,$_to,$_amount,$_date ) = mysql_fetch_row( $sql2 ) ) {		
					$remaining = $remaining - $_amount;
					$d = split("-", str_replace(" ", "-", $_date) );
					$_date = $d[2].".".$d[1].".".$d[0];
					echo "<tr>	<td align=left>$_date:</td>	<td align=right><font color=red>- &euro; ".number_format( $_amount, 2, ",", "")."</font></td>	</tr>\n";
					}
				$remaining = number_format( $remaining, 2, ",", "" );
				echo "</table>\n<hr/>&euro; $remaining";
				}
			}

		echo	"</td>\n";

		echo "<td>	<a href='Loeschen.php?transaction_number=$transaction_number'><img border=0 src='../themes/default/images/delete.gif'></a>	</td>	</tr>\n";
		}
	}

/*
		elseif ( $i == 5 or $i == 8 ) { # Von, An
			if ( $r[3] != "EINZAHLUNG" or $i != 5 ) {
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
		echo "\t\t\t\t<td align=center>$value</td>\n";
		}
	}
*/


echo "		</table>
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
