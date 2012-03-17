<?php

class renderer {
	function wa_header()
	{
//			add_js_ufile("themes/default/renderer.js");
		page(_($help_context = "Klientenkonten Carpe Diem e.V."), false, true);
	}

	function wa_footer()
	{
		end_page(false, true);
	}

	function menu_header($title, $no_menu, $is_index)
	{
		#$title = "Verwaltung";
		include("/var/www/frontaccount/includes/header.php");
		echo "<br/><br/>";

		global $path_to_root, $help_base_url, $db_connections;
		echo "<table class='callout_main' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "<tr>\n";
		echo "<td colspan='2' rowspan='2'>\n";

		echo "<table class='main_page' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "<tr>\n";
		echo "<td>\n";
		echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "<tr>\n";
		echo "<td class='quick_menu'>\n";
		if (!$no_menu)
		{
			$applications = $_SESSION['App']->applications;
			$local_path_to_root = $path_to_root;
			$sel_app = $_SESSION['sel_app'];
			echo "<table cellpadding=0 cellspacing=0 width='100%'><tr><td>";
			echo "<div class=tabs>";
			foreach($applications as $app)
			{
				$acc = access_string($app->name);
				echo "<a ".($sel_app == $app->id ? 
					("class='selected'") : "")
					." href='$local_path_to_root/index.php?application=".$app->id
					."'$acc[1]>" .$acc[0] . "</a>";
			}
			echo "</div>";
			echo "</td></tr></table>";

			echo "<table class=logoutBar>";
			echo "	<tr>	<td class=headingtext3>Angemeldet: " . $_SESSION["wa_current_user"]->real_name . "</td>";
#				echo "		<td class='logoutBarRight'><img id='ajaxmark' src='$path_to_root/themes/".user_theme(). "/images/ajax-loader.gif' align='center' style='visibility:hidden;'></td>";
			echo "		<td class='logoutBarRight'>";# <a href='$path_to_root/admin/display_prefs.php?'>" . _("Preferences") . "</a>&nbsp;&nbsp;&nbsp;\n";
			echo "			<img src='$local_path_to_root/themes/default/images/login.gif' width='14' height='14' border='0' alt='Passwort &auml;ndern'>&nbsp;&nbsp;";
			echo "			<a href='$path_to_root/admin/change_current_user_password.php?selected_id=" . $_SESSION["wa_current_user"]->username . "'>Passwort &auml;ndern</a>&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "			<img src='$local_path_to_root/themes/default/images/cancel.png' width='14' height='14' border='0' alt='Abmelden'>&nbsp;&nbsp;";
			echo "			<a href='$local_path_to_root/access/logout.php'>Abmelden</a>&nbsp;&nbsp;&nbsp;";
			echo "		</td>	</tr>";
			echo "	<tr><td colspan=3></td></tr>";
			echo "</table>";
		}
		echo "</td></tr></table>";

		if ($no_menu)
			echo "<br>";
		elseif ($title && !$is_index)
		{
			echo "<center><table id='title'><tr><td class='titletext'>$title</td>"
			."<td align=right><nobr><a href='javascript:window.print()'><img src='/accounting/themes/default/images/print.png' border=0 />&nbsp;Drucken</a></nobr></td>"
			."</tr></table></center>";
		}
	}

	function menu_footer($no_menu, $is_index)
	{
		global $version, $allow_demo_mode, $app_title, $power_url, 
			$power_by, $path_to_root, $Pagehelp, $Ajax;
		include_once($path_to_root . "/includes/date_functions.inc");

		if ($no_menu == false)
		{
			if ($is_index)
				echo "<table class=bottomBar>\n";
			else
				echo "<table class=bottomBar2>\n";
			echo "<tr>";
			if (isset($_SESSION['wa_current_user'])) {
				$phelp = implode('; ', $Pagehelp);
				echo "<td class=bottomBarCell align=right>" . Today() . " | " . Now() . " Uhr</td>\n";
				$Ajax->addUpdate(true, 'hotkeyshelp', $phelp);
				echo "<td id='hotkeyshelp'>".$phelp."</td>";
			}
			echo "</tr></table>\n";
		}
		echo "</td></tr></table></td>\n";
		echo "</table>\n";
		include("/var/www/frontaccount/includes/footer.php");
/*			if ($no_menu == false)
		{
			echo "<table align='center' id='footer'>\n";
			echo "<tr>\n";
			echo "<td align='center' class='footer'><a target='_blank' href='$power_url' tabindex='-1'><font color='#ffffff'>$app_title $version - " . _("Theme:") . " " . user_theme() . " - ".show_users_online()."</font></a></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td align='center' class='footer'><a target='_blank' href='$power_url' tabindex='-1'><font color='#ffff00'>$power_by</font></a></td>\n";
			echo "</tr>\n";
			if ($allow_demo_mode==true)
			{
				echo "<tr>\n";
				//echo "<td><br><div align='center'><a href='http://sourceforge.net'><img src='http://sourceforge.net/sflogo.php?group_id=89967&amp;type=5' alt='SourceForge.net Logo' width='210' height='62' border='0' align='middle' /></a></div></td>\n";
				echo "</tr>\n";
			}
			echo "</table><br><br>\n";
		}*/
	}

	function display_applications(&$waapp)
	{
		global $path_to_root;

		$selected_app = $waapp->get_selected_application();

		$img = "<img src='$path_to_root/themes/default/images/right.gif' style='vertical-align:middle;' width='17' height='17' border='0'>&nbsp;&nbsp;";
		foreach ($selected_app->modules as $module)
		{
			// image
			echo "<tr>";
			// values
			echo "<td valign='top' class='menu_group'>";
			echo "<table border=0 width='100%'>";
			echo "<tr><td class='menu_group'>";
			echo $module->name;
			echo "</td></tr><tr>";
			echo "<td class='menu_group_items'>";

			foreach ($module->lappfunctions as $appfunction)
			{
				if ($appfunction->label == "")
					echo "&nbsp;<br>";
				else {
					echo $img.menu_link($appfunction->link, $appfunction->label)."<br>\n";
				}
			}
			echo "</td>";
			if (sizeof($module->rappfunctions) > 0)
			{
				echo "<td width='50%' class='menu_group_items'>";
				foreach ($module->rappfunctions as $appfunction)
				{
					if ($appfunction->label == "")
						echo "&nbsp;<br>";
					else
					{
							echo $img.menu_link($appfunction->link, $appfunction->label)."<br>\n";
					}
				}
				echo "</td>";
			}

			echo "</tr></table></td></tr>";
		}

		echo "</table>";
	}
}

?>