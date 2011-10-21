<?php

function output_html($text)
{
	global $before_box, $Ajax, $messages;
	// Fatal errors are not send to error_handler,
	// so we must check the output
	if ($text && preg_match('/\bFatal error(<.*?>)?:(.*)/i', $text, $m)) {
		$Ajax->aCommands = array();  // Don't update page via ajax on errors
		$text = preg_replace('/\bFatal error(<.*?>)?:(.*)/i','', $text);
		$messages[] = array(E_ERROR, $m[2], null, null);
	}
	$Ajax->run();
	return  in_ajax() ? fmt_errors() : ($before_box.fmt_errors().$text);
}
//----------------------------------------------------------------------------------------

function kill_login()
{
	session_unset();
	session_destroy();
}
//----------------------------------------------------------------------------------------

function login_fail()
{
	global $path_to_root;
	
	header("HTTP/1.1 401 Authorization Required");

echo "<html>

<head>
<title>Anmeldung fehlgeschlagen</title>
<meta http-equiv='Content-type' content='text/html; charset=iso-8859-1' />
<link href='./themes/default/default.css' rel='stylesheet' type='text/css'> 
</head>

<body id='loginscreen'>
<center>\n";

$title = "Anmeldung";
include("/var/www/frontaccount/includes/header.php");

echo "<div id='_page_body'>
<br/><br/>

<table class='login' cellpadding=2 cellspacing=0 width='100%'>
<tr>
	<td align='center'>
		<center>
		<br/><br/>
		<font size='5' color='red'><b>Anmeldung fehlgeschlagen<b></font>
		<br/><br/>
		<b>Diese Kombination von<br/>
		Benutzername und Passwort ist ung&uuml;ltig !<b>
		<br/><br/>
		<a href='$path_to_root/index.php'><< Zur&uuml;ck zur Anmeldung</a>
		</center>
	</td>
</tr>
</table>
</div>";
	include("/var/www/frontaccount/includes/footer.php");
	kill_login();
	die();
}

//----------------------------------------------------------------------------------------

function check_page_security($page_security)
{
	return 0;
}

/*
	Helper function for setting page security level depeding on 
	GET start variable and/or some value stored in session variable.
	Before the call $page_security should be set to default page_security value.
*/
function set_page_security($value=null, $trans = array(), $gtrans = array())
{
	global $page_security;

	// first check is this is not start page call
	foreach($gtrans as $key => $area)
		if (isset($_GET[$key])) {
			$page_security = $area;
			return;
		}

	// then check session value
	if (isset($trans[$value])) {
		$page_security = $trans[$value];
		return;
	}
}

//-----------------------------------------------------------------------------
//	Removing magic quotes from nested arrays/variables
//
function strip_quotes($data)
{
	if(get_magic_quotes_gpc()) {
		if(is_array($data)) {
			foreach($data as $k => $v) {
				$data[$k] = strip_quotes($data[$k]);
			}
		} else
			return stripslashes($data);
	}
	return $data;
}

//============================================================================
if (!isset($path_to_root))
{
	$path_to_root = ".";
}

if (isset($_GET['path_to_root']) || isset($_POST['path_to_root']))
	header("Location: /accounting/index.php");

include_once($path_to_root . "/includes/current_user.inc");
include_once($path_to_root . "/frontaccounting.php");
include_once($path_to_root . "/admin/db/security_db.inc");
include_once($path_to_root . "/includes/lang/language.php");
include_once($path_to_root . "/config_db.php");
include_once($path_to_root . "/includes/ajax.inc");
include_once($path_to_root . "/includes/ui/ui_msgs.inc");
include_once($path_to_root . "/includes/prefs/sysprefs.inc");

/*
	Uncomment the setting below when using FA on shared hosting
	to avoid unexpeced session timeouts.
	Make sure this directory exists and is writable!
*/
//ini_set('session.save_path', dirname(__FILE__).'/../tmp/');

ini_set('session.gc_maxlifetime', 36000); // 10hrs

session_name('FA'.md5(dirname(__FILE__)));
//include_once($path_to_root.'/modules/www_statistics/includes/db_sessions.inc');
session_start();

// this is to fix the "back-do-you-want-to-refresh" issue - thanx PHPFreaks
header("Cache-control: private");

include_once($path_to_root . "/config.php");
get_text_init();

include_once($path_to_root . "/includes/main.inc");

// Ajax communication object
$Ajax = new Ajax();

// js/php validation rules container
$Validate = array();
// bindings for editors
$Editors = array();
// page help. Currently help for function keys.
$Pagehelp = array();

$Refs = new references();

// intercept all output to destroy it in case of ajax call
register_shutdown_function('end_flush');
ob_start('output_html',0);

// colect all error msgs
set_error_handler('error_handler' /*, errtypes */);

if (!isset($_SESSION["wa_current_user"]))
	$_SESSION["wa_current_user"] = new current_user();

if (strstr($_SERVER['PHP_SELF'], 'logout.php') == false) {

	if ( !$_SESSION["wa_current_user"]->logged_in() ) {
		if (!isset($_POST["user_name_entry_field"]) or $_POST["user_name_entry_field"] == "") {
			// strip ajax marker from uri, to force synchronous page reload
			$_SESSION['timeout'] = array( 'uri'=>preg_replace('/JsHttpRequest=(?:(\d+)-)?([^&]+)/s',
					'', @$_SERVER['REQUEST_URI']), 
				'post' => $_POST);
			include($path_to_root . "/access/login.php");
			if (in_ajax())
				$Ajax->activate('_page_body');
			exit;
		} else {		# Try to use posted inputs to login
			$succeed =  $_SESSION["wa_current_user"]->login($_POST["user_name_entry_field"], md5($_POST["password"]));
			if (!$succeed) {
				login_fail();
				}
			}
	} else
		set_global_connection();

	if (!isset($_SESSION["App"])) {
		$_SESSION["App"] = new front_accounting();
		$_SESSION["App"]->init();
		}
	}

$SysPrefs = &$_SESSION['SysPrefs'];

// POST vars cleanup needed for direct reuse.
// We quote all values later with db_escape() before db update.
$_POST = strip_quotes($_POST);

// GET cleanup against XSS. (NB in FA those are mainly numeric transaction numbers)
	foreach($_GET as $name => $value) {
//		$value = @html_entity_decode($value, ENT_QUOTES, $_SESSION['language']->encoding);
		$_GET[$name] = @htmlspecialchars($value, ENT_QUOTES, $_SESSION['language']->encoding);

	}
	foreach($_POST as $name => $value) {
//		$value = @html_entity_decode($value, ENT_QUOTES, $_SESSION['language']->encoding);
		$_POST[$name] = @htmlspecialchars($value, ENT_QUOTES, $_SESSION['language']->encoding);

	}

?>