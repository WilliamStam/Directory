<?php
namespace controllers;
use \timer as timer;
use \models as models;
class map extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		
		
		
		
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "map",
			"sub_section"=> "map",
			"template"   => "map",
			"meta"       => array(
				"title"=> "Directory | Map",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->output();
	}
}
