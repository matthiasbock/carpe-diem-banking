<?php

echo "
<html>

<head>
<title>Auszahlung aus Kasse</title>
<meta http-equiv='Content-type' content='text/html; charset='><link href='../themes/default/default.css' rel='stylesheet' type='text/css'>
<script type='text/javascript'>
window.print();
</script>
</head>

<body>

<center>
<br/>
<br/>
<br/>

<table class='tablestyle2' cellpadding=2 cellspacing=0>
	<tr valign=top><td>
		<table class='tablestyle_inner' cellpadding=7 cellspacing=7>
			<tr>
				<td colspan=3 class=tableheader><h3>Auszahlung</h3></td>
			</tr>
			<tr>
				<td class='label'>Betrag in &euro;:</td>
				<td colspan=2 align=right>&euro; ".$_GET['amount']."</td>
			</tr>
			<tr>
				<td class='label'>Datum (TT.MM.JJJJ):</td>
				<td colspan=2 align=right>".$_GET['dateText']."</td>
			</tr>
			<tr>
				<td class='label'>Aus Kasse:</td>
				<td colspan=2 align=right>".$_GET['fromText']."</td>
			</tr>
			<tr>
				<td class='label'>An Klient:</td>
				<td colspan=2 align=right>".$_GET['toText']."</td>
			</tr>
			<tr>
				<td class='label'>Verwendungszweck:<br/><small>(optional)</small></td>
				<td colspan=2><pre>".str_replace( "\n", "<br/>", $_GET['memo'] )."</pre></td>
			</tr>
			<tr>
				<td class='label'></td>
				<td align=right>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/><hr/><small>".$_GET['signer1']."</small></td>
				<td align=right>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/><hr/><small>".$_GET['signer2']."</small></td>
			</tr>
		</table>
	</td>
	</tr>
</table>

</center>

</body>

</html>";

?>
