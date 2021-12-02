<?php

if (!defined("DIONA")) {
	header("HTTP/1.1 403 Forbidden");
	header("Location: /");
	die("ERROR");
}

class template {
	
	var $dir = "";
	var $template = null;
	var $copy_template = null;
	var $data = Array();
	var $result = Array();
	
    function __construct(){
		
		$this->dir = 'template/';
		
	}
	
	function _clear() {
		
		$this->data = array ();
		
		$this->copy_template = $this->template;
	
	}

	function global_clear() {
		
		$this->data = array();
		
		$this->result = array();
		
		$this->copy_template = null;
		
		$this->template = null;
	
	}

	function load_template($tpl_name) {
		
		if (!file_exists($this->dir . "/" . $tpl_name )) {
			
			$this->template = "Файл не найден: " . $this->dir . "/" . $tpl_name;
			
			$this->copy_template = $this->template;
			
			return "";
			
		}

		$this->template = file_get_contents( $this->dir . "/" . $tpl_name );

		$this->copy_template = $this->template;
		
		return true;
		
	}
	
	function set($name, $var) {
		
		$var = str_replace(array("{", "["),array("_&#123;_", "_&#91;_"), $var);
			
		$this->data[$name] = $var;
		
	}	
	
	function compile($tpl) {
		
		foreach ($this->data as $key_find => $key_replace) {
			
			$find[] = $key_find;
			
			$replace[] = $key_replace;
			
		}
		
		$this->copy_template = str_ireplace( @$find, @$replace, $this->copy_template );
		
		$this->copy_template = str_replace(array("_&#123;_", "_&#91;_"), array("{", "["), $this->copy_template);
		
		if (isset($this->result[$tpl]))
			$this->result[$tpl] .= $this->copy_template;
		else
			$this->result[$tpl] = $this->copy_template;
		
		$this->_clear();
		
	}
	
}

?>