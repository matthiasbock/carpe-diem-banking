<?php

class transactions_app extends application
{
	function transactions_app()
	{
		$this->application("transactions", $this->help_context = "�bersicht");

		$this->add_module(" ");
		$this->add_lapp_function(0, "Transaktions�bersicht",
			"Transaktionen/Liste.php");
		$this->add_lapp_function(0, "Neue Transaktion",
			"index.php?application=banking");

		$this->add_extensions();
	}
}


?>
