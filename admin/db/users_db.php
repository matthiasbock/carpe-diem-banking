<?php


function add_user($user_id, $real_name, $password, $phone, $email) {
	$sql = "INSERT INTO users (user_id, password, real_name, phone, email, theme, startup_tab, inactive) VALUES ('$user_id', '$password', '$real_name', '$phone', '$email', 'default', 'banking', '0');";

	db_query($sql, "Benutzer konnte nicht angelegt werden.");
	}

function update_user_password($real_name, $password) {
	$sql = "UPDATE users SET password='$password' WHERE real_name='$real_name';";

	db_query($sql, "Passwort konnte nicht geändert werden");
	}

function update_user($user_id, $real_name, $phone, $email) {
	$sql = "UPDATE users SET real_name='$real_name', phone='$phone', email='$email' WHERE user_id='$user_id';";

	db_query($sql, "Benutzer $user_id konnte nicht aktualisiert werden.");
	}

function update_user_prefs($user_id, $prefs) {
	$sql = "UPDATE users SET ";
	foreach ($prefs as $name => $value) {
		$prefs[$name] = $name.'='.$value;
		}
	$sql .= implode(',', $prefs) . " WHERE user_id=".$user_id;

	return db_query($sql, "Einstellungen für $user_id konnten nicht aktualisiert werden.");
	}

function get_user($user_id) {
	$sql = "SELECT * FROM users WHERE user_id=".$user_id;

	$result = db_query($sql, "Benutzer $user_id konnte nicht abgerufen werden.");

	return db_fetch($result);
	}

function delete_user($user_id) {
	$sql="DELETE FROM users WHERE user_id=".$user_id;

	db_query($sql, "$user_id konnte nicht gelöscht werden.");
	}

?>