<?php
namespace controllers\admin\save;
use models as models;

class items extends _ {
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


	function _save() {
		$ID = (isset($_GET['ID']) && $_GET['ID']) ? $_GET['ID'] : "";
		if (in_array($ID, array("undefined"))) $ID = "";
		
	//test_array($_POST); 
		$values = array(
				"name" => $this->post("name","Required"),
				"categoryID" => $this->post("categoryID","Required"),
				"dateChanged" => date("Y-m-d H:i:s"),
				"email" => $this->post("email"),
				"phone" => $this->post("phone"),
				"phone_alt" => $this->post("phone_alt"),
				"website" => $this->post("website"),
				"website_alt" => $this->post("website_alt"),
				"photo" => $this->post("photo"),
				"photo_alt" => $this->post("photo_alt"),
				"text" => $this->post("text"),
				"synopsis" => $this->post("synopsis"),
				"address" => $this->post("address"),
				"gps_long" => $this->post("gps_long"),
				"gps_lat" => $this->post("gps_lat"),
				"recommended" => $this->post("recommended"),
				
		
		);
		
		//test_array($values); 
	//	test_array($_POST); 
		
		$errors = $this->errors;
		$photos = array();
		
		 // photos-new-6  photos-1
		foreach($_POST as $key=>$value){
			if (substr($key,0,7)=="photos-"){
				$key = str_replace("photos-","",$key);
				
				if (substr($key,0,4)=="new-"){
					$key = "";
				} 
				$item = array(
						"ID"=>$key,
						"photo"=>$value
				);
				$photos[] = $item;
			}
			
		}
		
				
		$values['photos'] = $photos;
		
		$result = array();
		$result['errors'] = $errors;
		$result['data'] = array();
		
		if (!count($errors)) {
			$result['data'] = array("ID" => models\items::save($ID, $values));
		}
		
		
		return $GLOBALS["output"]['data'] = $result;
	}
	function _delete() {
		$ID = (isset($_GET['ID']) && $_GET['ID']) ? $_GET['ID'] : "";
		
		return $GLOBALS["output"]['data'] = models\categories::remove($ID);
	}
	
	function upload(){
		$return = "";
		
		$filename = isset($_REQUEST['name'])?$_REQUEST['name']:"";
		
		
		$return = $this->uploader("files",$filename);
		return $GLOBALS["output"]['data'] = $return;
	}
	
	function photo_delete(){
		$return = "done";
		
		$ID = isset($_REQUEST['ID'])?$_REQUEST['ID']:"";
		
		
		$art = new \DB\SQL\Mapper($this->f3->get("DB"), "dir_items_photos");
		$art->load("ID='$ID'");
		$art->erase();
		
		
		
		return $GLOBALS["output"]['data'] = $return;
	}




}
