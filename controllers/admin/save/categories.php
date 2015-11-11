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
		);
		$errors = $this->errors;
		
		
		
		
		
		$result = array();
		$result['errors'] = $errors;
		$result['data'] = array();
		
		if (!count($errors)) {
			//$result['data'] = array("ID" => models\categories::save($ID, $values));
		}
		
		
		return $GLOBALS["output"]['data'] = $result;
	}

	




}
