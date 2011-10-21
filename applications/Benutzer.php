<?php

class users_app extends application
{
	function users_app()
	{
		$this->application("users", $this->help_context = "&Loginverwaltung (nur Admin)");

		$this->add_module(" ");
		$this->add_lapp_function(0, "Liste",
			"Benutzer/Liste.php");
		$this->add_lapp_function(0, "Aktivitaet",
			"Benutzer/Aktivitaet.php");
		$this->add_lapp_function(0, "Neu",
			"Benutzer/Neu.php");
		$this->add_lapp_function(0, "Bearbeiten",
			"Benutzer/Bearbeiten.php");
		$this->add_lapp_function(0, "Entfernen",
			"Benutzer/Loeschen.php");

		$this->add_extensions();
	}
}


?>
