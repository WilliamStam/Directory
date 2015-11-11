<?php
namespace controllers\admin\data;
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


	function data() {
		$domain = $this->f3->get("domain");
		$result = array();
		$ID = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : "";
		
		
		$details = new models\categories();
		$details = $details->get($ID);
		$result['details'] = $details;
		
		$where = "";
		
		
		
		
		$records = models\categories::getInstance()->getAll($where, "category ASC");
		$result['records'] = models\categories::format($records,true);
		

		return $GLOBALS["output"]['data'] = $result;
	}

	




}
