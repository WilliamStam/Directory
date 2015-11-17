<?php
namespace controllers;
use \timer as timer;
use \models as models;
class home extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		
		
		$recent = models\items::format(models\items::getInstance()->getAll("",'datein DESC', '0,5'));
		$items = models\items::format(models\items::getInstance()->getAll("recommended='1' and dir_items.photo != ''"));
		$rand = array_rand($items,10);
		
		$n = array();
		foreach ($rand as $i){
			$n[] = $items[$i];
		}
		$suggested = $n;
		//test_array(($n));
		
		//test_array($suggested);
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "home",
			"sub_section"=> "home",
			"template"   => "home",
			"meta"       => array(
				"title"=> "Directory | Home",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->suggested = $suggested;
		$tmpl->recent = $recent;
		$tmpl->output();
	}
}
