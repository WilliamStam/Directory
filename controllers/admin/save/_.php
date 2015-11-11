<?php
namespace controllers\admin\save;
use models as models;

class _ extends \controllers\admin\_ {
	public $errors;
	function __construct() {
		parent::__construct();
		$this->user = $this->f3->get("user");
		$this->f3->set("__runJSON",true);
		
		
	}
	
	function post($key, $required = false) {
		$val = isset($_POST[$key]) ? $_POST[$key] : "";
		if ($required && $val == "") {
			$this->errors[$key] = $required === true ? "" : $required;
		}
		return $val;
	}

}
