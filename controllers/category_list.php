<?php
namespace controllers;
use \timer as timer;
use \models as models;
class category_list extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		
		
		
		
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "category_list",
			"sub_section"=> "category_list",
			"template"   => "category_list",
			"meta"       => array(
				"title"=> "Directory | Categories",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->output();
	}
}
