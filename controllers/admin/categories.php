<?php
namespace controllers\admin;
use \timer as timer;
use \models as models;
class categories extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		
		
		$categories = models\categories::getInstance()->getAll("","category ASC");
		
		
		$tmpl = new \template("template.twig","app/admin/");
		$tmpl->page = array(
			"section"    => "categories",
			"sub_section"=> "categories",
			"template"   => "categories",
			"meta"       => array(
				"title"=> "Directory | Admin | Categories",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->categories = $categories;
		$tmpl->output();
	}
}
