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

		$applications = $_SESSION['App']->applications;
		$sel_app = $_SESSION['sel_app'];

		echo "<table class='callout_main' border='0' cellpadding='0' cellspacing='0'>
		<tr>
		<td colspan='2' rowspan='2'>

		<table class='main_page' border='0' cellpadding='0' cellspacing='0'>";

		if ( !$no_menu )
			echo "
		<tr><td class='quickmenu'><table width='100%' cellspacing=10>
			<tr>	<td width='*'>&nbsp;</td>
				<td width='130'>
					<a href='javascript:window.print()'>
					<img src='$path_to_root/themes/default/images/print.png' width='48' border='0' alt='Drucken'><br/>
					<p style='float:left;'>Seite ausdrucken</p>
					</a>
				</td>
				<td width='130'>
					<a href='$path_to_root/admin/change_current_user_password.php'>
					<img src='$path_to_root/themes/default/images/key.png' width='48' border='0' alt='Passwort &auml;ndern'><br/>
					<p style='float:left;'>Passwort &auml;ndern</p>
					</a>
				</td>
				<td width='130'>
					<a href='$path_to_root/Benutzer/Liste.php'>
					<img src='$path_to_root/themes/default/images/Sozialarbeiter.png' width='52' border='0' alt='Passwort &auml;ndern'><br/>
					<p style='float:left;'>Benutzer verwalten</p>
					</a>
				</td>
				<td width='130'>
					<a href='$path_to_root/access/logout.php'>
					<img src='$path_to_root/themes/default/images/logout.png' height='48' border='0' alt='Abmelden'><br/>
					<p style='float:left;'>Abmelden</p>
					</a>
				</td>
			</tr>
		</table></td></tr>";

		echo "
		<tr>
		<td>
		<table width='100%' border='0' cellpadding='0' cellspacing='0'>
		<tr>
		<td class='quick_menu'>

		<table cellpadding=0 cellspacing=0 width='100%'><tr><td>
		<div class=tabs>";
		if ( !$no_menu )
			foreach($applications as $app)
			{
				$acc = access_string($app->name);
				echo "<a ".($sel_app == $app->id ? 
					("class='selected'") : "")
					." href='$path_to_root/index.php?application=".$app->id
					."'$acc[1]>" .$acc[0] . "</a>";
			}
		echo "</div>";
		echo "</td></tr></table>";

		$user=$_SESSION["wa_current_user"]->real_name;
		if ( $user == "" ) $user="nicht angemeldet";
		echo "<table class=logoutBar>";
		echo "	<tr>	<td class=headingtext2 rowspan=2 align=right>Benutzer: ".$user."</td> </tr>";
		echo "	<tr>	<td>&nbsp;</td>	</tr>";
		echo "</table>";

		echo "</td></tr></table>";
		if ( !isset($_GET['application']) and !($_SERVER['SCRIPT_FILENAME'] == "/var/www/accounting/index.php") )
			echo "<br/><br/>";

/*		if ($no_menu)
			echo "<br>";
		elseif ($title && !$is_index)
		{
			echo "<center><table id='title'><tr><td class='titletext'>$title</td>"
			."<td align=right><nobr><a href='javascript:window.print()'><img src='/accounting/themes/default/images/print.png' border=0 />&nbsp;Drucken</a></nobr></td>"
			."</tr></table></center>";
		}*/
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
					if ( stristr($appfunction->label,"Liste") !== FALSE or stristr($appfunction->label,"übersicht") !== FALSE )
						$img = "<img src='$path_to_root/themes/default/images/list.png' style='vertical-align:middle;' width=32 border='0'/>";
					elseif ( stristr($appfunction->label,"Neu") !== FALSE )
						$img = "<img src='$path_to_root/themes/default/images/new.png' style='vertical-align:middle;' width=32 border='0'/>";
					elseif ( stristr($appfunction->label,"Formular") !== FALSE )
						$img = "<img src='$path_to_root/themes/default/images/pdf.png' style='vertical-align:middle;' width=32 border='0'/>";
					else
						$img = "<img src='$path_to_root/themes/default/images/right.gif' style='vertical-align:middle;' width='17' height='17' border='0'>&nbsp;&nbsp;";
					echo menu_link($appfunction->link, $appfunction->label, null, $img)."<br>\n";
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
