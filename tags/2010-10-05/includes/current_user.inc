<?php

include_once("/var/www/frontaccount/includes/prefs/userprefs.inc");

class current_user
{
	var $user_id;
	var $real_name;
	var $logged;
	
	var $prefs;

	function current_user()
	{
		$this->user_id = $this->real_name = "";
		$this->logged = FALSE;

		$this->prefs = new user_prefs();
	}

	function logged_in()
	{
		return $this->logged;
	}

	function login($user_id, $password)
	{
		global $path_to_root, $conn, $db;

		$this->logged = false;

		include_once("/var/www/frontaccount/config_db.php");
		$db = mysql_connect($conn["host"] , $conn["dbuser"], $conn["dbpassword"]);
		mysql_select_db($conn["dbname"], $db);

		$query = mysql_query("SELECT * FROM users WHERE user_id = '$user_id' AND ( password = CONVERT( _utf8 '$password' USING latin1 ) OR password = '' );");
		$result = mysql_fetch_row( $query );

		if ( $result ) {
			$this->user_id = $result[0];
			$this->real_name = $result[2];
			$this->prefs = new user_prefs($result);
			mysql_query("UPDATE users SET last_visit_date='".date("Y-m-d H:i:s")."' WHERE user_id='$user_id';");
			$this->logged = TRUE;
			}

		return $this->logged;
	}

	function get_db_connection()
	{
		global $conn;

		$db = mysql_connect($conn["host"], $conn["dbuser"], $conn["dbpassword"]);
		mysql_select_db($conn["dbname"], $db);

		return $db;
	}

	function update_prefs($prefs)
	{
		update_user_prefs($this->user_id, $prefs);
		$this->prefs = new user_prefs( get_user($this->user_id) );
	}
}

//--------------------------------------------------------------------------

function round2($number, $decimals=2)
{
	$delta = ($number < 0 ? -.0000000001 : .0000000001);
	return round($number+$delta, $decimals);
}

/*
	Returns number formatted according to user setup and using $decimals digits after dot 
	(defualt is 0). When $decimals is set to 'max' maximum available precision is used 
	(decimals depend on value) and trailing zeros are trimmed.
*/
function number_format2($number, $decimals=2)
{
	global $thoseps, $decseps;
	$tsep = "";
	$dsep = ",";
	//return number_format($number, $decimals, $dsep,	$tsep);
	if($decimals==='max')
		$dec = 15 - floor(log10(abs($number)));
	else {
		$delta = ($number < 0 ? -.0000000001 : .0000000001);
		$number += $delta;
		$dec = $decimals;
	}

	$num = number_format($number, $dec, $dsep, $tsep);

	return $decimals==='max' ? rtrim($num, '0') : $num;

}
//
//	Current ui mode.
//
function fallback_mode() {
    return True;
}

function price_format($number) {
    return number_format2($number, 2);
}

function price_decimal_format($number, &$dec)
{
	$dec = user_price_dec();
	$str = strval($number);
	$pos = strpos($str, '.');
	if ($pos !== false)
	{
		$len = strlen(substr($str, $pos + 1));
		if ($len > $dec)
			$dec = $len;
	}
	return number_format2($number, $dec);
}
// function money_format doesn't exist in OS Win.
if (!function_exists('money_format'))
{
	function money_format($format, $number) 
	{
		return price_format($number);
	} 
}	

// 2008-06-15. Added extra parameter $stock_id and reference for $dec
//--------------------------------------------------------------------
function qty_format($number, $stock_id=null, &$dec) {
	$dec = get_qty_dec($stock_id);
    return number_format2($number, $dec);
}

// and get_qty_dec
function get_qty_dec($stock_id=null)
{
	return 2;
}
//-------------------------------------------------------------------
//
//	Maximum precision format. Strips trailing unsignificant digits.
//
function maxprec_format($number) {
    return number_format2($number, 'max');
}

function percent_format($number) {
    return number_format2($number, 2);
}

function user_numeric($input) {
    global $decseps, $thoseps;

    $num = trim($input);
    $sep = "";
    if ($sep!='')
    	$num = str_replace( $sep, '', $num);

    $sep = ",";
    if ($sep!='.')
    	$num = str_replace( $sep, '.', $num);

    if (!is_numeric($num))
	  return false;
    $num = (float)$num;
    if ($num == (int)$num)
	  return (int)$num;
    else
	  return $num;
}

function user_company()
{
	global $def_coy;
	
	return isset($_SESSION["wa_current_user"]) ? $_SESSION["wa_current_user"]->company : $def_coy;
}

function user_theme()
{
	return isset($_SESSION["wa_current_user"]) ? $_SESSION["wa_current_user"]->prefs->get_theme() : 'default';
}

function user_pagesize()
{
	return "A4";
}

function user_startup_tab()
{
	return $_SESSION["wa_current_user"]->prefs->start_up_tab();
}

function set_user_prefs($prefs)
{
	$_SESSION["wa_current_user"]->update_prefs($prefs);
}

function add_user_js_data() {
	global $path_to_root, $thoseps, $decseps, $date_system, $dateseps;

	$ts = "";
	$ds = ",";

    $js = "\n"
	  . "var user = {\n"
	  . "theme: '". $path_to_root . '/themes/'. user_theme().'/'."',\n"
	  . "loadtxt: 'Abruf...',\n"
	  . "date: '".date("d.m.Y")."',\n"	// server date
	  . "datesys: ".$date_system.",\n"
	  . "ts: '$ts',\n"
	  . "ds: '$ds' }\n";

  add_js_source($js);
}

//--------------------------------------------------------------------------

function session_timeout()
{
	return false;
}

//-----------------------------------------------------------------------------
//	Inserts $elements into $array at position $index.
//	$elements is list of any objects
//
function array_insert(&$array, $index, $elements)
{
	if (!is_array($elements)) $elements = array($elements);

	$head  = array_splice($array, 0, $index);
	$array = array_merge($head, $elements, $array);
}

function array_remove(&$array, $index, $len=1)
{
	array_splice($array, $index, $len);
}

function array_substitute(&$array, $index, $len, $elements)
{
	array_splice($array, $index, $len);
	array_insert($array, $index, $elements);
}

function array_append(&$array, $elements)
{
	foreach($elements as $key => $el) {
		if(is_int($key))
			$array[] = $el;
		else
			$array[$key] = $el;
	}
}
//
//	Search $needle in $haystack or in $haystack[][$valuekey]
//	returns $needle found or null.
//
function array_search_value($needle, $haystack, $valuekey=null)
{
	foreach($haystack as $key => $value) {
		$val = isset($valuekey) ? @$value[$valuekey] : $value;
		if ($needle == $val){
			return $value;
		}
	}
	return null;
}
//
//	Search $needle in $haystack or in $haystack[][$valuekey]
//	returns array of keys of $haystack elements found
//
function array_search_keys($needle, $haystack, $valuekey=null)
{
	$keys = array();
	if($haystack)
		foreach($haystack as $key => $value) {
			$val = isset($valuekey) ? @$value[$valuekey] : $value;
			if ($needle == $val){
				$keys[] = $key;
			}
		}
	return $keys;
}
//
//	Find first (single) $needle in $haystack or in $haystack[][$valuekey]
//	returns $haystack element found or null
//
function array_search_key($needle, $haystack, $valuekey=null)
{
	$keys = array_search_keys($needle, $haystack, $valuekey);
	return @$keys[0];
}

function flush_dir($path, $wipe = false) 
{
	$dir = opendir($path);
	while(false !== ($fname = readdir($dir))) {
		if($fname=='.' || $fname=='..' || $fname=='CVS' || (!$wipe && $fname=='index.php')) continue;
  		if(is_dir($path.'/'.$fname)) {
		    flush_dir($path.'/'.$fname, $wipe);
		    if ($wipe) @rmdir($path.'/'.$fname);
		} else
		    @unlink($path.'/'.$fname);
	}
}
/*
	Returns current path to company private folder.
	(Current path can change after chdir).
*/
function company_path($comp=null)
{
	global $path_to_root;
	return "./company/0";
}

?>