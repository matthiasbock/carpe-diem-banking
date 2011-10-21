<?php

class users_app extends application
{
	function users_app()
	{
		$this->application("users", $this->help_context = "&Loginverwaltung (nur Admin)");

		$this->add_module(" ");
		$this->add_lapp_function(0, "Benutzerverwaltung starten",
			"Benutzer/Liste.php");

		$this->add_extensions();
	}
}


?>
