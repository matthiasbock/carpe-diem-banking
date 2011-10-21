<?php

class general_ledger_app extends application
{
	function general_ledger_app()
	{
		$this->application("banking", _($this->help_context = "&Banking"));

		$this->add_module(" ");
		$this->add_lapp_function(0, "Geldeingang verbuchen",	"Banking/Gutschrift.php"); 
		$this->add_lapp_function(0, "Zuteilung vornehmen",	"Banking/Zuteilung.php");
		$this->add_lapp_function(0, "Einzahlung in Kasse",	"Banking/Einzahlung.php");
		$this->add_lapp_function(0, "Auszahlung aus Kasse",	"Banking/Auszahlung.php");
		$this->add_lapp_function(0, "Uberweisung an Kreditor",	"Banking/Kreditor.php");

		$this->add_extensions();
	}
}

?>
