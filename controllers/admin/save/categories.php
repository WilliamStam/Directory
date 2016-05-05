<?php
namespace controllers\admin\save;
use models as models;

class categories extends _ {
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
				"category" => $this->post("category","Required"),
				"text" => $this->post("text"),
				"photo" => $this->post("photo"),
				"relationship" => is_array($this->post("relationship"))?implode(",",$this->post("relationship")):"",
		);
		$errors = $this->errors;
		
		
		//test_array($values); 
		
		
		$result = array();
		$result['errors'] = $errors;
		$result['data'] = array();
		
		if (!count($errors)) {
			$result['data'] = array("ID" => models\categories::save($ID, $values));
		}
		
		
		return $GLOBALS["output"]['data'] = $result;
	}
	function _delete() {
		$ID = (isset($_GET['ID']) && $_GET['ID']) ? $_GET['ID'] : "";
		
		return $GLOBALS["output"]['data'] = models\categories::remove($ID);
	}
	function order() {
		$return = "";
		$domain = $this->f3->get("domain");
		//$ID = (isset($_GET['ID'])) ? $_GET['ID'] : "";
		
		$f3 = \Base::instance();
		
		$list = $_POST['id'];
		
		
		$a = new \DB\SQL\Mapper($f3->get("DB"), "dir_categories");
		
		$sort = array();
		foreach ($list as $id => $parentId) {
			
			$a->load("ID='$id'");
			$a->parentID = $parentId;
			if (!$a->dry()){
				$a->save();
			}
			
			
			$a->reset();
			
		}
		
		
		test_array($sort);
		
		
		return $GLOBALS["output"]['data'] = $return;
	}
	function upload(){
		$return = "";
		
		$filename = isset($_REQUEST['name'])?$_REQUEST['name']:"";
		
		
		$return = $this->uploader("categories",$filename);
		return $GLOBALS["output"]['data'] = $return;
	}




}
