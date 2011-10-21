<?php

class menu_item 
{
	var $label;
	var $link;
	
	function menu_item($label, $link) 
	{
		$this->label = $label;
		$this->link = $link;
	}
}

class menu 
{
	var $title;
	var $items;
	
	function menu($title) 
	{
		$this->title = $title;
		$this->items = array();
	}
	
	function add_item($label, $link) 
	{
		$item = new menu_item($label,$link);
		array_push($this->items,$item);
		return $item;
	}
	
}

class app_function 
{
	var $label;
	var $link;
	var $access;
	
	function app_function($label,$link,$access='SA_OPEN') 
	{
		$this->label = $label;
		$this->link = $link;
		$this->access = $access;
	}
}

class module 
{
	var $name;
	var $icon;
	var $lappfunctions;
	var $rappfunctions;
	
	function module($name,$icon = null) 
	{
		$this->name = $name;
		$this->icon = $icon;
		$this->lappfunctions = array();
		$this->rappfunctions = array();
	}
	
	function add_lapp_function($label,$link="",$access='SA_OPEN') 
	{
		$appfunction = new app_function($label,$link,$access);
		//array_push($this->lappfunctions,$appfunction);
		$this->lappfunctions[] = $appfunction;
		return $appfunction;
	}

	function add_rapp_function($label,$link="",$access='SA_OPEN') 
	{
		$appfunction = new app_function($label,$link,$access);
		//array_push($this->rappfunctions,$appfunction);
		$this->rappfunctions[] = $appfunction;
		return $appfunction;
	}
	
	
}

class application 
{
	var $id;
	var $name;
	var $help_context;
	var $modules;
	var $enabled;
	
	function application($id, $name, $enabled=true) 
	{
		$this->id = $id;
		$this->name = $name;
		$this->enabled = $enabled;
		$this->modules = array();
	}
	
	function add_module($name, $icon = null) 
	{
		$module = new module($name,$icon);
		//array_push($this->modules,$module);
		$this->modules[] = $module;
		return $module;
	}
	
	function add_lapp_function($level, $label,$link="",$access='SA_OPEN') 
	{
		$this->modules[$level]->lappfunctions[] = new app_function($label, $link, $access);
	}
	
	function add_rapp_function($level, $label,$link="",$access='SA_OPEN') 
	{
		$this->modules[$level]->rappfunctions[] = new app_function($label, $link, $access);
	}
	
	function add_extensions()
	{
		global $installed_extensions, $path_to_root;

		foreach ($installed_extensions as $mod)
		{
			if (@$mod['active'] && isset($mod['entries']))
				foreach($mod['entries'] as $entry) {
					if ($entry['tab_id'] == $this->id) {
						set_ext_domain($mod['path']);
//							$_SESSION['get_text']->add_domain($_SESSION['language']->code, 
//								$mod['path']."/lang");

						$this->add_rapp_function(
							isset($entry['section']) ? $entry['section'] : 2,
							_($entry['title']), $path_to_root.'/'.$mod['path'].'/'.$entry['url'],
							isset($entry["access"]) ? $entry["access"] : 'SA_OPEN' );

						set_ext_domain();
//							$_SESSION['get_text']->add_domain($_SESSION['language']->code, 
//								$path_to_root."/lang", $_SESSION['language']->version);
					}
				}
		}
	}
	//
	// Helper returning link to report class added by extension module.
	//
	function report_class_url($class)
	{
		global $installed_extensions;

		// TODO : konwencja lub ?
		$classno = 7;
//			if (file_exists($path_to_root.'/'.$mod['path'].'/'.$entry['url']
//					.'/'.'reporting/reports_custom.php'))
			return "reporting/reports_main.php?Class=".$class;
//			else
//				return '';
	}
}

?>