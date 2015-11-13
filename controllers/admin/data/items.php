<?php
namespace controllers\admin\data;
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


	function data() {
		$domain = $this->f3->get("domain");
		$result = array();
		$ID = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : "";
		$catID = isset($_REQUEST['catID']) ? $_REQUEST['catID'] : "";
		
		
		$details = new models\items();
		$details = $details->get($ID);
		$details['categories'] = "";
		$result['details'] = $details;
		
		$where = "";
		
		if ($catID){
			$where = "catID = '{$catID}'";
		}
		
		$cats = models\categories::getInstance()->getAll("itemID='{$details['ID']}'");
		if (count($cats)){
			$n = array();
			foreach($cats as $item){
				$n[] = $item["ID"];
			}
			$result['details']['categories'] = implode(",",$n);
		} 
		
		
		//test_array($cats); 
		
		
		
		
		
		$records = models\items::getInstance()->getAll($where, "name ASC");
		$result['records'] = models\items::format($records,true);
		

		return $GLOBALS["output"]['data'] = $result;
	}

	




}
