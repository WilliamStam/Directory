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
		$highlight = isset($_REQUEST['highlight'])?$_REQUEST['highlight']:false;
		
		$details = models\items::getInstance()->get($ID);
		$details = models\items::format($details,$highlight);
		
		$categories = models\categories::format(models\categories::getInstance()->getAll("dir_items.ID='{$details['ID']}'","category ASC"),false,$highlight);
		
		$title = $details['name'];
		
	//	test_array($category); 
	//	test_array(array("breadcrumbs"=>$this->breadcrumb,"categories"=>$c)); 
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "details",
			"sub_section"=> "item-".$details['ID'],
			"template"   => "item_details",
			"meta"       => array(
				"title"=> "Directory | {$this->f3->scrub($title)}",
			),
			"css"=>"",
				"js"=>"http://maps.google.com/maps/api/js",
		);
		$tmpl->details = $details;
		$tmpl->categories = $categories;
		$tmpl->output();
	}
	
}
