<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/
$page_security = 'SA_OPEN';
$path_to_root="..";
include($path_to_root . "/includes/session.inc");

include($path_to_root . "/includes/page/header.inc");
page_header("Abgemeldet", true, false, '', get_js_png_fix());

echo "<table width='100%' border='0'>
	<tr>
		<td align=center><font size=3><strong>Sie haben sich abgemeldet.</strong></font></td>
	</tr>
  <tr>
	<td align='center'><img src='../themes/default/images/logo_frontaccounting.png' alt='Carpe Diem e.V.' border='0' /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align='center'><font size=2><strong>Auf Wiedersehen !</strong><br/>
</font></div></td>
  </tr>
  <tr>
    <td><div align='center'>";
echo "<a href='$path_to_root/index.php'><b><< Zur&uuml;ck zur Anmeldung</b></a>";
echo "</div></td>
  </tr>
</table>
<br>\n";
end_page(false, true);
session_unset();
session_destroy();
?>


