<?php
namespace controllers;
use \timer as timer;
use \models as models;
class _items extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		
		
		
		
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "items",
			"sub_section"=> "items",
			"template"   => "_items",
			"meta"       => array(
				"title"=> "Directory | Items",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->items = models\items::format(models\items::getInstance()->getAll());
		$tmpl->output();
	}
}
