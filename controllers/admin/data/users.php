<?php
namespace controllers\admin\data;
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


	function data() {
		$domain = $this->f3->get("domain");
		$result = array();
		$ID = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : "";
		
		
		$details = new models\users();
		$details = $details->get($ID);
		$result['details'] = $details;
		
		$where = "";
		
		
		
		
		$records = models\users::getInstance()->getAll($where, "name ASC");
		$result['records'] = models\users::format($records,true);
		

		return $GLOBALS["output"]['data'] = $result;
	}

	




}
