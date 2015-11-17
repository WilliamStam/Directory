<?php
namespace controllers\admin;
use \timer as timer;
use \models as models;
class home extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		
	
		
		
		$tmpl = new \template("template.twig","app/admin/");
		$tmpl->page = array(
			"section"    => "home",
			"sub_section"=> "home",
			"template"   => "home",
			"meta"       => array(
				"title"=> "Directory | Admin | Home",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->output();
	}
}
