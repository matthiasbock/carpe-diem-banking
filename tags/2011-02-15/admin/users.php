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
$page_security = 'SA_USERS';
$path_to_root = "..";
include_once($path_to_root . "/includes/session.inc");

page(_($help_context = "Users"));

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");

include_once($path_to_root . "/admin/db/users_db.php");

simple_page_mode(true);
//-------------------------------------------------------------------------------------------------

function can_process() 
{

	if (strlen($_POST['user_id']) < 4)
	{
		display_error("Der Benutzername muss mindestens 4 Zeichen lang sein.");
		set_focus('user_id');
		return false;
	}

	return true;
}

//-------------------------------------------------------------------------------------------------

if ($Mode=='ADD_ITEM' || $Mode=='UPDATE_ITEM') 
{

	if (can_process())
	{
    	if ($selected_id != -1) 
    	{
    		update_user_prefs($selected_id, get_post( array('user_id', 'real_name', 'phone', 'email') ) );

    		if ($_POST['password'] != "")
    			update_user_password($selected_id, $_POST['user_id'], md5($_POST['password']));

    		display_notification_centered("Benutzer wurde ge�ndert.");
    	} 
    	else 
    	{
    		add_user($_POST['user_id'], $_POST['real_name'], md5($_POST['password']), $_POST['phone'], $_POST['email']);
			$id = db_insert_id();
			// use current user display preferences as start point for new user
			$prefs = $_SESSION['wa_current_user']->prefs->get_all();
			
			update_user_prefs($id, $prefs);

			display_notification_centered("Neuer Benutzer hinzugef�gt.");
    	}
		$Mode = 'RESET';
	}
}

//-------------------------------------------------------------------------------------------------

if ($Mode == 'Delete')
{
	delete_user($selected_id);
	display_notification_centered("Benutzer gel�scht");
	$Mode = 'RESET';
}

//-------------------------------------------------------------------------------------------------
if ($Mode == 'RESET')
{
 	$selected_id = -1;
	$sav = get_post('show_inactive');
	unset($_POST);	// clean all input fields
	$_POST['show_inactive'] = $sav;
}

$result = get_users(check_value('show_inactive'));
start_form();
start_table(TABLESTYLE);

$th = array("Benutzername (intern)", "Vollst�ndiger Name", "Telefonnummer", "Email-Adresse", "Letzte Anmeldung")

inactive_control_column($th);
table_header($th);	

$k = 0; //row colour counter

while ($myrow = db_fetch($result)) 
{

	alt_table_row_color($k);

	$last_visit_date = sql2date($myrow["last_visit_date"]);

	/*The security_headings array is defined in config.php */
	$not_me = strcasecmp($myrow["user_id"], $_SESSION["wa_current_user"]->username);

	label_cell($myrow["user_id"]);
	label_cell($myrow["real_name"]);
	label_cell($myrow["phone"]);
	email_cell($myrow["email"]);
	label_cell($last_visit_date, "nowrap");
#	label_cell($myrow["role"]);
	
    if ($not_me)
		inactive_control_cell($myrow["id"], $myrow["inactive"], 'users', 'id');
	elseif (check_value('show_inactive'))
		label_cell('');

	edit_button_cell("Edit".$myrow["id"], _("Edit"));
    if ($not_me)
 		delete_button_cell("Delete".$myrow["id"], _("Delete"));
	else
		label_cell('');
	end_row();

} //END WHILE LIST LOOP

inactive_control_row($th);
end_table(1);
//-------------------------------------------------------------------------------------------------
start_table(TABLESTYLE2);

$_POST['email'] = "";
if ($selected_id != -1) 
{
  	if ($Mode == 'Edit') {
		//editing an existing User
		$myrow = get_user($selected_id);

#		$_POST['id'] = $myrow["id"];
		$_POST['user_id'] = $myrow["user_id"];
		$_POST['real_name'] = $myrow["real_name"];
		$_POST['phone'] = $myrow["phone"];
		$_POST['email'] = $myrow["email"];
#		$_POST['role_id'] = $myrow["role_id"];
#		$_POST['language'] = $myrow["language"];
#		$_POST['print_profile'] = $myrow["print_profile"];
#		$_POST['rep_popup'] = $myrow["rep_popup"];
#		$_POST['pos'] = $myrow["pos"];
	}
	hidden('selected_id', $selected_id);
	hidden('user_id');

	start_row();
	label_row(_("User login:"), $_POST['user_id']);
} 
else 
{ //end of if $selected_id only do the else when a new record is being entered
	text_row(_("User Login:"), "user_id",  null, 22, 20);
#	$_POST['language'] = user_language();
#	$_POST['print_profile'] = user_print_profile();
#	$_POST['rep_popup'] = user_rep_popup();
#	$_POST['pos'] = user_pos();
}
$_POST['password'] = "";
password_row(_("Password:"), 'password', $_POST['password']);

if ($selected_id != -1) 
{
	table_section_title(_("Enter a new password to change, leave empty to keep current."));
}

text_row_ex("Volst�ndiger Name:", 'real_name',  50);

text_row_ex("Telefonnummer:", 'phone', 30);

email_row_ex("Email-Adresse:", 'email', 50);

#security_roles_list_row(_("Access Level:"), 'role_id', null); 

#languages_list_row(_("Language:"), 'language', null);

#pos_list_row(_("User's POS"). ':', 'pos', null);

#print_profiles_list_row(_("Printing profile"). ':', 'print_profile', null,
#	_('Browser printing support'));

#check_row(_("Use popup window for reports:"), 'rep_popup', $_POST['rep_popup'],
#	false, _('Set this option to on if your browser directly supports pdf files'));

end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();
end_page();
?>