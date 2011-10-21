<?php

$page_security = '';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(800, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

page($help_context = "Benutzerliste", false, false, "", $js);

echo "
<center>
<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner' cellspacing=5 cellpadding=5>
			<tr>
				<td colspan=8 class=tableheader><h3>Liste aller Benutzer</h3></td>
			</tr>
			<tr>
				<td colspan=14>
				<a href='Neu.php'>
				<img border=0 src='../themes/default/images/new.gif'>
				Neuer Benutzer</a>
				</td>
			</tr>
			<tr>
				<td></td>
				<td class=tableheader>Nr</td>
				<td class=tableheader>Name</td>
				<td class=tableheader>Konto</td>
				<td class=tableheader>Telefonnummer</td>
				<td class=tableheader>Email</td>
				<td class=tableheader>Letzter Besuch</td>
				<td class=tableheader>Aktionen</td>
			</tr>\n";
$style = "evenrow";
$sql = mysql_query("SELECT user_id,real_name,phone,email,last_visit_date,inactive FROM users ORDER BY last_visit_date DESC;");
while ( $r = mysql_fetch_row($sql) ) {
	if ( $r[5] == "0" ) { # not inactive
		echo "\t\t\t<tr class=$style>
					<td><img border=0 alt='Benutzer' src='../themes/default/images/user.png' /></td>
					<td># $r[0]</td>
					<td>$r[1]</td>
					<td align=center>";
		if ( $sql2 = mysql_fetch_row( mysql_query("SELECT account_number FROM bank_accounts WHERE account_name = '$r[1]';") ) ) {
			echo "Konto # $sql2[0]<br/>
<a href='../Konten/Bearbeiten.php?account_number=$sql2[0]'><img border=0 alt='Bearbeiten' src='../themes/default/images/edit.gif' />&nbsp;</a>
<a href='../Transaktionen/Liste.php?select=account&account_number=$sql2[0]'><img border=0 alt='Aktivit&auml;t' src='../themes/default/images/list.png' />&nbsp;</a>";
			}
		else 	echo "-nicht vorhanden-<br/><a href='../Konten/Neu.php?account_name=".urlencode($r[1])."'>[Neues Konto]</a>";
					echo "</td>
					<td>$r[2]</td>
					<td><a href='mailto:$r[3]'>$r[3]</a></td>
					<td>$r[4]</td>
					<td>
						<a href='Bearbeiten.php?user_id=$r[0]'><img border=0 alt='Bearbeiten' src='../themes/default/images/edit.gif' /></a>
						<a href='Aktivitaet.php?user_id=$r[0]'><img border=0 alt='Aktivit&auml;t' src='../themes/default/images/list.png' /></a>
						<a href='Loeschen.php?user_id=$r[0]'><img border=0 alt='L&ouml;schen' src='../themes/default/images/delete.gif' /></a>
					</td>
				</tr>\n";
		if ( $style == "evenrow" )
			$style = "oddrow";
		else	$style = "evenrow";
		}
	# else skip this user, don't display it
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
