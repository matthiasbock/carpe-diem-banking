<?php

class transactions_app extends application
{
	function transactions_app()
	{
		$this->application("transactions", $this->help_context = "Übersicht");

		$this->add_module(" ");
		$this->add_lapp_function(0, "Transaktionsübersicht",
			"Transaktionen/Liste.php");
		$this->add_lapp_function(0, "Neue Transaktion",
			"index.php?application=banking");

		$this->add_extensions();
	}
}


?>
