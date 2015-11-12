<?php
namespace controllers\admin\data;
use models as models;

class _ extends \controllers\admin\_ {
	function __construct() {
		parent::__construct();
		$this->user = $this->f3->get("user");
		$this->f3->set("__runJSON",true);
		if ($this->user['ID']==""){
			$this->f3->error(403);
		}
		
	}



}
