<?php
namespace controllers;
use \timer as timer;
use \models as models;
class list_category extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		$catID = $this->f3->get("PARAMS['catID']");
		$category = models\categories::getInstance()->get($catID);
		
		
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "categories",
			"sub_section"=> "$catID",
			"template"   => "list_category",
			"meta"       => array(
				"title"=> "Directory | {$category['category']}",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->category = $category;
		$tmpl->output();
	}
}
