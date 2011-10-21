<?php

class accounts_app extends application
{
	function accounts_app()
	{
		$this->application("accounts", $this->help_context = "Meine &Konten");

		$this->add_module("");
		$this->add_lapp_function(0, "Liste",
			"Konten/Liste.php");
		$this->add_lapp_function(0, "Neues Konto anlegen",
			"Konten/Neu.php");
#		$this->add_lapp_function(0, "Aufnahmeformular",
#			"Konten/Aufnahmeformular.pdf");

		$this->add_extensions();
	}
}


?>
