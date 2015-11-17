<?php
namespace controllers;
use \timer as timer;
use \models as models;
class contact extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		
		
		
		
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "contact",
			"sub_section"=> "contact",
			"template"   => "contact",
			"meta"       => array(
				"title"=> "Directory | Contact Details",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->output();
	}
}
