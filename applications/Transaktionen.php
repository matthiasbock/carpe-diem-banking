<?php

class transactions_app extends application
{
	function transactions_app()
	{
		$this->application("transactions", $this->help_context = "&Transaktionen");

		$this->add_module(" ");
		$this->add_lapp_function(0, "Liste",
			"Transaktionen/Liste.php");
		$this->add_lapp_function(0, "Neu",
			"index.php?application=banking");
/*		$this->add_lapp_function(0, "Bearbeiten",
			"Transaktionen/Liste.php");*/
		$this->add_lapp_function(0, "Entfernen",
			"Transaktionen/Liste.php");

		$this->add_extensions();
	}
}


?>
