<?php
namespace controllers\admin\save;
use models as models;

class users extends _ {
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
				"email" => $this->post("email","Required"),
		);
		
		
		
		
		if (isset($_POST['password'])&&$_POST['password']!=""){
			$password = $this->post("password");
			$password = md5(md5("dire-").$password.md5("-radiant"));
			$values['password'] = $password;
		} else {
			
			if ($ID==""){
				$this->errors['password'] = "Required";
			}
		}
	
		//test_array($this->errors); 
		
		$result = array();
		$result['errors'] = $this->errors;
		$result['data'] = array();
		
		if (!count($this->errors)) {
			$result['data'] = array("ID" => models\users::save($ID, $values));
		}
		
		
		return $GLOBALS["output"]['data'] = $result;
	}
	function _delete() {
		$ID = (isset($_GET['ID']) && $_GET['ID']) ? $_GET['ID'] : "";
		
		return $GLOBALS["output"]['data'] = models\users::remove($ID);
	}
}
