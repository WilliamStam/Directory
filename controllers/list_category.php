<?php
namespace controllers;
use \timer as timer;
use \models as models;
class list_category extends _ {
	function __construct(){
		$this->breadcrumb = array();
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		$catID = $this->f3->get("PARAMS['catID']");
		$category = models\categories::getInstance()->get($catID);
		$categories = $this->f3->get("categories");
		
		$c = array();
		foreach ($categories as $item){
			$c["ID-".$item['ID']] = $item;
		}
		
		$this->catparents($category,$c);
		$this->breadcrumb = array_reverse ($this->breadcrumb);
		$parentIDs = array();
		foreach($this->breadcrumb as $item){
			$parentIDs[] = $item['ID'];
		}
		$category['parentIDs'] = $parentIDs;
		
		
		
	//	test_array($c); 
		
		
		$items = array(
			"suggested"=>models\items::format(models\items::getInstance()->getAll("catID='{$category['ID']}' AND recommended='1'", "name ASC")),
			"other"=>models\items::format(models\items::getInstance()->getAll("catID='{$category['ID']}' AND recommended='0'", "name ASC")),
		);
		
		
		
		$relationships = array();
		
		if ($category['relationship']){
			$relationships =  models\categories::getInstance()->getAll("dir_categories.ID in ({$category['relationship']}) OR parentID='{$category['ID']}'");
		} 
		
		
		
	//	test_array($relationships); 
		
		
		
		
		
	//	test_array($category); 
	//	test_array(array("breadcrumbs"=>$this->breadcrumb,"categories"=>$c)); 
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "categories",
			"sub_section"=> $category['ID'],
			"template"   => "list_category",
			"meta"       => array(
				"title"=> "Directory | {$category['category']}",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->breadcrumb = $this->breadcrumb;
		$tmpl->category = $category;
		$tmpl->related = $relationships;
		$tmpl->items = $items;
		$tmpl->output();
	}
	function catparents($current,$categoryList){
		$this->breadcrumb[] = models\categories::format($current);
		if ($current['parentID']){
			
			$this->catparents($categoryList["ID-".$current['parentID']],$categoryList);
		}
	}
}
