<?php
namespace controllers;
use \timer as timer;
use \models as models;
class list_alphabet extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		$letter = $this->f3->get("PARAMS['letter']");
		//$category = models\categories::getInstance()->get($catID);
		
		$where = "name LIKE '$letter%'";
		$where_cat = "category LIKE '$letter%'";
		
		if ($letter=="other"){
			$where = 'ucase(mid(name, 1, 1)) not between "A" and "Z"';
			$where_cat = 'ucase(mid(category, 1, 1)) not between "A" and "Z"';
		}
		
		$items = array(
				"suggested"=>models\items::format(models\items::getInstance()->getAll("$where AND recommended='1'", "name ASC")),
				"other"=>models\items::format(models\items::getInstance()->getAll("$where AND recommended='0'", "name ASC")),
		);
		$categories = models\categories::format(models\categories::getInstance()->getAll("$where_cat", "category ASC"));
		
		//test_array($items); 
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "alphabet",
			"sub_section"=> "$letter",
			"template"   => "list_alphabet",
			"meta"       => array(
				"title"=> "Directory | starts with '$letter'",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->items = $items;
		$tmpl->categories = $categories;
		$tmpl->letter = $letter;
		$tmpl->output();
	}
}
