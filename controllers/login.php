<?php
namespace controllers;
use \timer as timer;
use \models as models;
class login extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		
		
		
		
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "login",
			"sub_section"=> "login",
			"template"   => "login",
			"meta"       => array(
				"title"=> "Directory | Admin | Login",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->output();
	}
}
