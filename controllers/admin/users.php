<?php
namespace controllers\admin;
use \timer as timer;
use \models as models;
class users extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		
		
		
		
		
		$tmpl = new \template("template.twig","app/admin/");
		$tmpl->page = array(
			"section"    => "users",
			"sub_section"=> "users",
			"template"   => "users",
			"meta"       => array(
				"title"=> "Directory | Admin | Users",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->output();
	}
}
