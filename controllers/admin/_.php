<?php
namespace controllers\admin;
use \timer as timer;
use \models as models;
class _ {

	function __construct() {
		$this->f3 = \base::instance();
		$this->user = $this->f3->get("user");

		if ($this->user['ID']==""){
			$this->f3->reroute("/login?msg=Login+Failed");
		}
		
		
	}


	
}
