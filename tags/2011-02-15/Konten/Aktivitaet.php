<?php

$account_number = $_GET['account_number'];

if ( $account_number == "" )
	header("Location:Liste.php");
else
	header("Location:../Transaktionen/Liste.php?select=account&account_number=$account_number");

?>
