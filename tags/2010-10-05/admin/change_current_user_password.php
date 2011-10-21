<?php

$page_security = '';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");

page($help_context = "Neues Passwort");

#include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");

include_once($path_to_root . "/admin/db/users_db.php");

function valid_input()
{
    	if (strlen($_POST['password']) == 0)
    	{
   		display_error("Ein leeres Passwort ist nicht erlaubt.");
 		set_focus('password');
    		return false;
    	}

   	if ($_POST['password'] != $_POST['passwordConfirm'])
   	{
   		display_error("Die Passw&ouml;rter stimmen nicht &uuml;berein.");
		set_focus('password');
   		return false;
   	}

	return true;
}

if (isset($_POST['UPDATE_ITEM']))
{
	if ( valid_input() )
	{
		update_user_password( $_SESSION["wa_current_user"]->real_name, md5($_POST['password']) );
		display_notification("Ihr Passwort wurde geändert.");
		$Ajax->activate('_page_body');
	}
}

start_form();

start_table(TABLESTYLE);

#$myrow = get_user($_SESSION["wa_current_user"]->user);

#label_row(_("User login:"), $myrow['user_id']);

table_section_title("Passwort &auml;ndern");

$_POST['password'] = "";
$_POST['passwordConfirm'] = "";

password_row("Neues Passwort:", 'password', $_POST['password']);
password_row("Passwort wiederholen:", 'passwordConfirm', $_POST['passwordConfirm']);

end_table(1);

submit_center( 'UPDATE_ITEM', "Passwort &auml;ndern", true, '',  'default');
end_form();
end_page();

?>
