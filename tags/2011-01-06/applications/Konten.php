<?php

class accounts_app extends application
{
	function accounts_app()
	{
		$this->application("accounts", $this->help_context = "Meine &Klienten");

		$this->add_module("");
		$this->add_lapp_function(0, "Liste",
			"Konten/Liste.php");
		$this->add_lapp_function(0, "Aktivitaet",
			"Konten/Aktivitaet.php");
		$this->add_lapp_function(0, "Neu",
			"Konten/Neu.php");
		$this->add_lapp_function(0, "Bearbeiten",
			"Konten/Bearbeiten.php");
		$this->add_lapp_function(0, "Entfernen",
			"Konten/Loeschen.php");

		$this->add_extensions();
	}
}


?>
