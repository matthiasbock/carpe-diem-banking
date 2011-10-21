<?php

function get_current_account_value( $to ) {

global $db, $db_connections;

$value = 0;

$result = mysql_query("SELECT amount FROM `bank_transactions` WHERE `to` = '$to';");
if ( $result )
	while ( $r = mysql_fetch_row($result) )
		$value = $value + floatval( $r[0] );

$result = mysql_query("SELECT amount FROM `bank_transactions` WHERE `from` = '$to';");
if ( $result )
	while ( $r = mysql_fetch_row($result) )
		$value = $value - floatval( $r[0] );

return $value;
}

?>
