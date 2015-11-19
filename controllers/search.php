<?php
namespace controllers;
use \timer as timer;
use \models as models;
class search extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		$search = isset($_REQUEST['search-box'])?$_REQUEST['search-box']:"";
		
		$columns = $this->f3->get("DB")->exec("EXPLAIN dir_items;");
		$str = array();
		foreach($columns as $item){
			$str[] = "dir_items.{$item['Field']} LIKE '%$search%'";
		}
		$str = implode(" OR ",$str);
		//test_array(array($str,$columns));
		
		$where = "($str) ";
		$where_cat = "(dir_categories.category LIKE '%$search%' OR dir_categories.text  LIKE '%$search%') ";
		
		
		
		$items = array(
				"suggested"=>models\items::format(models\items::getInstance()->getAll("$where AND recommended='1'", "name ASC"),$search),
				"other"=>models\items::format(models\items::getInstance()->getAll("$where AND recommended='0'", "name ASC"),$search),
		);
		$categories = models\categories::format(models\categories::getInstance()->getAll("$where_cat", "category ASC"),false,$search);
		
		//test_array($search); 
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "search",
			"sub_section"=> "search",
			"template"   => "search",
			"meta"       => array(
				"title"=> "Directory | Search '$search'",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->items = $items;
		$tmpl->categories = $categories;
		$tmpl->search = $search;
		$tmpl->output();
	}
}
