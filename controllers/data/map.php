<?php
namespace controllers\data;
use models as models;

class map extends _data {
	private static $instance;
	public $meetingID;
	public $companyID;
	function __construct() {
		parent::__construct();

	}

	public static function getInstance(){
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	function data() {
		$domain = $this->f3->get("domain");
		$result = array();
		$catID = isset($_GET['catID'])?$_GET['catID']:"";
		
		$catWhewre = "AND catID ='$catID'";
		
		$items = models\items::format(models\items::getInstance()->getAll("gps_long!='' AND gps_lat!='' $catWhewre"));
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
		

		return $GLOBALS["output"]['data'] = $item_json;
	}

	




}
