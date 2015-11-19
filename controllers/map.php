<?php
namespace controllers;
use \timer as timer;
use \models as models;
class map extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		
		//$categories = $this->f3->get("categories");
		//$categories = models\categories::format($categories,true);
		
		//test_array($categories); 
		
		$items = models\items::format(models\items::getInstance()->getAll("gps_long!='' AND gps_lat!=''"));
		$item_json = array();
		foreach ($items as $item){
			
			$photo = "";
			if ($item['photo']){
				$photo = "<img alt='' src='/thumbnail/110/90?crop=false&file=/files/{$item['photo'] }' class='img-thumbnail' />";
			}
			$str  = "";
			if ($item['phone']){
				$str = $str . "<em class='dg'>Phone:</em> ".$item['phone']."<br>";
			}
			if ($item['website']){
				$str = $str . "<em class='dg'>Website:</em> ".$item['website']."<br>";
			}
			
			
			$item_json[] = array(
				"ID"=>$item['ID'],
				"placeTitle"=>$item['name'],	
				"tooltip"=>'<div class="scrollFix"><strong>'.$item["name"].'</strong><br><div>
<div class="c" style="margin:10px;">'.$photo.'</div><div>'.$str.'</div></div>',	
				"url"=>"/item/{$item['ID']}/{$item['url']}",	
				"lat"=>$item['gps_lat'],	
				"lng"=>$item['gps_long'],	
			);
		}
		
		
	//	test_array($item_json); 
		
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "map",
			"sub_section"=> "map",
			"template"   => "map",
			"meta"       => array(
				"title"=> "Directory | Map",
			),
			"css"=>"",
			"js"=>"http://maps.google.com/maps/api/js",
		);
		$tmpl->item_json = json_encode($item_json);
		$tmpl->output();
	}
}
