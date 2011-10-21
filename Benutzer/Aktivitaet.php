<?php

$user_id = $_GET['user_id'];

if ( $user_id == "" )
	header("Location:Liste.php");
else
	header("Location:../Transaktionen/Liste.php?select=user&user_id=$user_id");

?>
