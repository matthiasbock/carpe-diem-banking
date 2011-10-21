<?php

// $select = $_GET['select'];
// $type = $_GET['type'];

$page_security = '';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(800, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

page($help_context = "Kontenübersicht", false, false, "", $js);

echo "
<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner' cellspacing=5 cellpadding=5>
			<tr>
				<td colspan=11 class=tableheader>
				<h3>Konten</h3>
				</td>
			</tr>
			<tr>
				<td colspan=14>
				<a href='Neu.php'>
				<img border=0 src='../themes/default/images/new.gif'>
				Neues Konto</a>
				</td>
			</tr>
			<tr>
				<td></td>
				<td class=tableheader>#</td>
				<td class=tableheader>Typ</td>
				<td class=tableheader>Name</td>
				<td class=tableheader>Erstellt</td>
				<td class=tableheader>Konto-Nr.</td>
				<td class=tableheader>BLZ</td>
				<td class=tableheader>Kreditinstitut</td>
				<td class=tableheader>Kasse</td>
				<td class=tableheader>Sozialarbeiter</td>
				<td class=tableheader>Aktionen</td>
			</tr>\n";
$style = "evenrow";
$sql = mysql_query("SELECT account_number,account_type,account_name,created,bank_account_number,bank_code,bank_name,cash_account,social_worker FROM bank_accounts ORDER BY account_type DESC, created DESC;");
while ( $r = mysql_fetch_row($sql) ) {
	echo "\t\t\t<tr class=$style>\n";
	echo "\t\t\t\t<td><img border=0 alt='Aktivit&auml;t' src='../themes/default/images/account.png' /></td>\n";
	for ($i = 0; $i < 9; $i++) {
		$value = $r[$i];
		if ( $i > 3 and $i < 7 ) { # Bank-Konto Nr,BLZ,Inst.
			if ( $r[1] != "SOZIALARBEITER" )
				$value = "-";
			echo "\t\t\t\t<td align=center>$value</td>\n";
			}
		elseif ( $i == 7 or $i == 8 ) { # Kasse, Sozialarbeiter
			if ( $value != "0" ) {
				$sql2 = mysql_fetch_row( mysql_query("SELECT account_number,account_name FROM bank_accounts WHERE account_number = '$value' OR account_name = '$value' LIMIT 1") );
				$nr = $sql2[0];
				$name = $sql2[1];
				$value = "$name<br/>
<a href='Bearbeiten.php?account_number=$nr'><img border=0 alt='Bearbeiten' src='../themes/default/images/edit.gif' />&nbsp;</a>
<a href='Aktivitaet.php?account_number=$nr'><img border=0 alt='Aktivit&auml;t' src='../themes/default/images/list.png' />&nbsp;</a>";
				}
			else	$value = "-";
			echo "\t\t\t\t<td align=center>$value</td>\n";
			}
		else	echo "\t\t\t\t<td>$value</td>\n";
		}
echo"				<td>
					<a href='Bearbeiten.php?account_number=$r[0]'><img border=0 alt='Bearbeiten' src='../themes/default/images/edit.gif' /></a>
					<a href='Aktivitaet.php?account_number=$r[0]'><img border=0 alt='Aktivit&auml;t' src='../themes/default/images/list.png' /></a>
					<a href='Loeschen.php?account_number=$r[0]'><img border=0 alt='L&ouml;schen' src='../themes/default/images/delete.gif' /></a>
				</td>\n";
	echo "\t\t\t</tr>\n";
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
