<?php
namespace controllers\admin;
use \timer as timer;
use \models as models;
class items extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		
		
		
		
		
		$tmpl = new \template("template.twig","app/admin/");
		$tmpl->page = array(
			"section"    => "items",
			"sub_section"=> "items",
			"template"   => "items",
			"meta"       => array(
				"title"=> "Directory | Admin | Items",
			),
			"css"=>"",
				"js"=>"http://maps.google.com/maps/api/js",
		);
		$tmpl->output();
	}
}
