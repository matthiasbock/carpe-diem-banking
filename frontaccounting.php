<?php

if (!isset($path_to_root) || isset($_GET['path_to_root']) || isset($_POST['path_to_root']))
	header("Location: /accounting/index.php");

include_once($path_to_root . '/applications/application.php');
include_once($path_to_root . '/applications/Banking.php');
include_once($path_to_root . '/applications/Benutzer.php');
include_once($path_to_root . '/applications/Konten.php');
include_once($path_to_root . '/applications/Transaktionen.php');
include_once($path_to_root . '/installed_extensions.php');

foreach ($installed_extensions as $ext)
{
	if (@($ext['active'] && isset($ext['tabs'])))
		foreach($ext['tabs'] as $tab) {
			include_once($path_to_root.'/'.$ext['path'].'/'.$tab['url']);
		}
}

class front_accounting
	{
	var $user;
	var $settings;
	var $applications;
	var $selected_application;
	// GUI
	var $menu;
	//var $renderer;

	function front_accounting()
		{
			//$this->renderer =& new renderer();
		}
	function add_application(&$app)
		{	
			if ($app->enabled) // skip inactive modules
				$this->applications[$app->id] = &$app;
		}
	function get_application($id)
		{
		 if (isset($this->applications[$id]))
			return $this->applications[$id];
		 return null;
		}
	function get_selected_application()
		{
		if (isset($this->selected_application))
			 return $this->applications[$this->selected_application];
		foreach ($this->applications as $application)
			return $application;
		return null;
		}
	function display()
		{
		global $path_to_root;
		
		include_once($path_to_root . "/themes/cool/renderer.php");

		$this->init();
		$rend = new renderer();
		$rend->wa_header();
		//$rend->menu_header($this->menu);
		$rend->display_applications($this);
		//$rend->menu_footer($this->menu);
		$rend->wa_footer();
		$this->renderer =& $rend;
		}
	function init()
		{
		global $installed_extensions, $path_to_root;

#		$this->menu = new menu("Hauptmen�");
#		$this->menu->add_item("Hauptmen�", "index.php");
#		$this->menu->add_item("Abmelden", "/account/access/logout.php");
		$this->applications = array();
		$this->add_application(new general_ledger_app());
		$this->add_application(new accounts_app());
		$this->add_application(new transactions_app());
#		$this->add_application(new users_app());

		// Do not use global array directly here, or you suffer 
		// from buggy php behaviour (unexpected loop break 
		// because of same var usage in class constructor).
		$extensions = $installed_extensions;
		foreach ($extensions as $ext)
		{
			if (@($ext['active'] && isset($ext['tabs']))) {
				set_ext_domain($ext['path']);
				foreach($ext['tabs'] as $tab) {
					$class = $tab['tab_id']."_app";
					if (class_exists($class))
						$this->add_application(new $class());
				}
			}
		}
		set_ext_domain();
#		$this->add_application(new setup_app());
		}
}
?>
