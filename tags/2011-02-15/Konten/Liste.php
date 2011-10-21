<?php

$page_security = '';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");

page($help_context = "Kontenübersicht", false, false, "", "");

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
				<td class=tableheader>Name</td>
				<td class=tableheader>Bankverbindung</td>
				<td class=tableheader>Kasse</td>
				<td class=tableheader>Kontostand</td>
				<td class=tableheader>Aktionen</td>
			</tr>\n";
$sql = mysql_fetch_row( mysql_query("SELECT bank_account FROM users WHERE user_id='".$_SESSION['wa_current_user']->user_id."'") );
$myaccount = $sql[0];

$style = "evenrow";
if ( $_SESSION['wa_current_user']->user_id != 1 )
	$sql = mysql_query("SELECT account_number,account_name,account_type,birthday,bank_account_number,bank_code,bank_name,cash_account FROM bank_accounts WHERE ( (account_type='KLIENT' AND (social_worker='$myaccount' OR social_worker='0') ) OR (account_type='GELDGEBER') OR (account_type='SOZIALARBEITER' AND account_number='$myaccount') OR (account_type='KASSE') ) ORDER BY account_type DESC, created DESC;");
else	$sql = mysql_query("SELECT account_number,account_name,account_type,birthday,bank_account_number,bank_code,bank_name,cash_account FROM bank_accounts ORDER BY account_type DESC, created DESC;");
while ( $r = mysql_fetch_row($sql) ) {
	$account_number=$r[0];
	$account_name=$r[1];
	$account_type=$r[2];
	$birthday=$r[3];
	$bank_account_number=$r[4];
	$bank_code=$r[5];
	$bank_name=$r[6];
	$cash_account=$r[7];
		$sql2 = mysql_fetch_row( mysql_query("SELECT account_name FROM bank_accounts WHERE account_type='KASSE' AND account_number='$cash_account' LIMIT 1") );
		if ( $sql2 )	$cash_account_name = $sql2[0];
		else		$cash_account_name = "Fehler: Kasse $cash_account nicht gefunden";
	$social_worker=$r[8];
	
					# Name
	echo "\t\t\t<tr class=$style>
		\t<td>$account_name";
	if ( $birthday != "" ) echo "&nbsp;&nbsp;&nbsp;<i>* $birthday</i>";
	echo "<br/><small><i>$account_type</i></small></td>\n";

	if ( $account_type == "SOZIALARBEITER" )	# Bankverbindung
		echo "\t\t\t<td>Konto-Nr. $bank_account_number<br/>$bank_code $bank_name</td>";
	else	echo "\t\t\t<td><center>---</center></td>";

	if ( $account_type == "SOZIALARBEITER" )	# Kasse
		echo "\t\t\t<td>$cash_account_name</td>";
	else	echo "\t\t\t<td><center>---</center></td>";

	# Kontostand
	echo "<td align=right><b>0,00 &euro;</b></td>\n";

	# Aktionen
	echo"\t\t\t<td>
		\t\t<a href='Bearbeiten.php?account_number=$account_number'><img border=0 alt='Bearbeiten' src='../themes/default/images/edit.png' width=32 /></a>
		\t\t<a href='Aktivitaet.php?account_number=$account_number'><img border=0 alt='Aktivit&auml;t' src='../themes/default/images/list.png' width=32 /></a>
		\t\t<a href='Loeschen.php?account_number=$account_number'><img border=0 alt='L&ouml;schen' src='../themes/default/images/delete.png' width=32 /></a>
		\t</td>\n";

	echo "\t\t</tr>\n";

	if ( $style == "evenrow" )
		$style = "oddrow";
	else	$style = "evenrow";

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
