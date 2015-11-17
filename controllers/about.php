<?php
namespace controllers;
use \timer as timer;
use \models as models;
class about extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		
		
		
		
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "about",
			"sub_section"=> "about",
			"template"   => "about",
			"meta"       => array(
				"title"=> "Directory | About Us",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->output();
	}
}
