<?php
namespace controllers;
use \timer as timer;
use \models as models;
class item_details extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		$ID = $this->f3->get("PARAMS['ID']");
		
		
		$details = models\items::getInstance()->get($ID);
		$details = models\items::format($details);
		
		
		
		
	//	test_array($category); 
	//	test_array(array("breadcrumbs"=>$this->breadcrumb,"categories"=>$c)); 
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "details",
			"sub_section"=> "item-".$details['ID'],
			"template"   => "item_details",
			"meta"       => array(
				"title"=> "Directory | {$details['name']}",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->details = $details;
		$tmpl->output();
	}
	
}
