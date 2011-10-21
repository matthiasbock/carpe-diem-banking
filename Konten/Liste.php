<?php

$page_security = '';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");

page($help_context = "Meine Konten", false, false, "", "");

# Meine Konten

echo "
<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner' cellspacing=5 cellpadding=5>
			<tr>
				<td colspan=11 class=tableheader>
				<h3>Meine Konten</h3>
				</td>
			</tr>
			<tr>
				<td colspan=4>
					<a href='/accounting/Konten/Neu.php'>
					<img border=0 height=48 alt='Neu' src='../themes/default/images/Neu.png' style='vertical-align:middle;' /> Neues Konto anlegen &nbsp;&nbsp;
					</a>
				</td>
			</tr>
			<tr>
				<td class=tableheader> </td>
				<td class=tableheader>Name</td>
				<td class=tableheader>Kontostand</td>
				<td class=tableheader> </td>
			</tr>
";

# Welches Konto gehört dem angemeldeten Benutzer ?
$sql = mysql_fetch_row( mysql_query("SELECT bank_account FROM users WHERE user_id='".$_SESSION['wa_current_user']->user_id."'") );
$myaccount = $sql[0];

if ( $_SESSION['wa_current_user']->user_id == 1 )	# Admin sieht alle Konten, Sozialarbeiter nur solche die für sie eine Rolle spielen
	$sql = mysql_query("SELECT account_number,account_name,account_type,birthday,bank_account_number,bank_code,bank_name,cash_account FROM bank_accounts ORDER BY account_type DESC, created DESC;");
else	$sql = mysql_query("SELECT account_number,account_name,account_type,birthday,bank_account_number,bank_code,bank_name,cash_account FROM bank_accounts WHERE ( (account_type='KLIENT' AND (social_worker='$myaccount' OR social_worker='0') ) OR (account_type='SOZIALARBEITER' AND account_number='$myaccount') OR (account_type='KASSE') OR (account_type='GELDGEBER') ) ORDER BY account_type DESC, created DESC;");

$style = "oddrow";
while ( list($account_number, $account_name, $account_type, $birthday, $bank_account_number, $bank_code, $bank_name, $cash_account) = mysql_fetch_row($sql) ) {	# ein Konto nach dem anderen prozessieren
	if ( $style == "evenrow" )
		$style = "oddrow";
	else	$style = "evenrow";
	echo "\t\t\t<tr class=$style>\n";

	if ( $account_type == "SOZIALARBEITER" )
		echo "\t\t\t<td><img border=0 alt='Sozialarbeiter' src='../themes/default/images/Sozialarbeiter.png' height=48 /></td>\n";
	elseif ( $account_type == "GELDGEBER" )
		echo "\t\t\t<td><img border=0 alt='Sozialarbeiter' src='../themes/default/images/Geldgeber.png' /></td>\n";
	elseif ( $account_type == "KASSE" )
		echo "\t\t\t<td><img border=0 alt='Sozialarbeiter' src='../themes/default/images/Geld.png' /></td>\n";
	else	echo "\t\t\t<td></td>\n";

	# Name
	echo "\t\t\t<td><a href='Bearbeiten.php?account_number=$account_number'>$account_name</a>";
	if ( $birthday != "" ) echo "&nbsp;&nbsp;&nbsp;<i>* $birthday</i>";
	echo "<br/><small><i>$account_type</i></small></td>\n";

#	if ( $account_type == "SOZIALARBEITER" )	# Bankverbindung
#		echo "\t\t\t<td>Konto-Nr. $bank_account_number<br/>$bank_code $bank_name</td>\n";
#	else	echo "\t\t\t<td><center>---</center></td>\n";

#	if ( $account_type == "SOZIALARBEITER" ) {	# Kasse
#		# Kasse Nr->Name
#		$sql2 = mysql_fetch_row( mysql_query("SELECT account_name FROM bank_accounts WHERE account_type='KASSE' AND account_number='$cash_account' LIMIT 1") );	
#		if ( $sql2 )	$cash_account_name = $sql2[0];
#		else		$cash_account_name = "Fehler: Kasse $cash_account nicht gefunden";
#		echo "\t\t\t<td>$cash_account_name</td>\n";
#		}
#	else	echo "\t\t\t<td><center>---</center></td>\n";

	if ( $account_type != "GELDGEBER" ) {	# Kontostand
		include_once("/var/www/accounting/includes/account_value.php");
		$v = get_current_account_value( $account_number );
		$value = number_format( $v, 2, ",", "" );
		echo "<td align=right><a href='Aktivitaet.php?account_number=$account_number'>";
		if ( $v < 0 )
			echo "<font size=2 color=red>";
		else	echo "<font size=2>";
		echo "<b>&euro; $value</b></font></a></td>\n";
		}
	else	echo "<td> </td>\n";

	# Aktionen
	if ( $_SESSION['wa_current_user']->user_id == 1 or $account_type == "KLIENT" )
		echo "\t\t\t<td>
			\t<a href='Loeschen.php?account_number=$account_number'><img border=0 alt='L&ouml;schen' src='../themes/default/images/delete.gif' /></a>
			</td>\n";
	else	echo "\t\t\t<td> </td>\n";

	echo "\t\t</tr>\n";
	}

echo "
		</table>
	</td>
	</tr>
</table>
</center>

<br/>

</center>
";

end_page();
?>
