<?php
namespace controllers;
use \timer as timer;
use \models as models;
class _categories extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		
		
		
		
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "categories",
			"sub_section"=> "categories",
			"template"   => "_categories",
			"meta"       => array(
				"title"=> "Directory | Categories",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->output();
	}
}
