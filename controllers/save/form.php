<?php
namespace controllers\save;

use models as models;

class form extends _save {
	private static $instance;
	private $errors;
	public $meetingID;
	public $companyID;

	function __construct() {
		parent::__construct();


	}

	function post($key, $required = false, $default="") {
		$val = isset($_POST[$key]) ? $_POST[$key] : "";
		if ($required && $val == "") {
			$this->errors[$key] = $required === true ? "" : $required;
		}
		if ($default!="" && $val ==""){
			$val = $default;
		}
		return $val;
	}




	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}




	function company() {
		$result = array();
		$user = $this->user;
		$ID = isset($_GET['ID']) ? $_GET['ID'] : "";
		$ID_orig = $ID;

		$values = array(
			"company" => $this->post("company", true),
			"invitecode" => $this->post("invitecode"),
			"admin_email" => $this->post("admin_email", "A valid email is Required"),

		);
		$errors = $this->errors;


		if ($values['invitecode'] == "") $values['invitecode'] = $values['company'] . "-" . md5($values['company'] . "meetpad" . date("dmyhis"));

		
		$companyO = models\company::getInstance();
		
		
		$exists = $companyO->getAll("company='{$values['company']}'");
		$exists = isset($exists[0])?$exists[0]:false;
		if ($exists && $exists['ID']!=$ID){
			$errors['company'] = "A company with that name already exists<br> Admin Contact: {$exists['admin_email']}";
		}
		//test_array($errors); 
		
		
		
		
		



		$groups = array();
		$categories = array();
		$groups_id = array();
		$categories_id = array();
		$groupCount = 0;
		$catCount = 0;

		foreach ($_POST as $key => $val) {
			if (strpos($key, "group-edit-") > -1) {
				$itemID = str_replace("group-edit-", '', $key);
				$groups_id[] = $itemID;
				$groups[] = array(
					"ID" => $itemID,
					"group" => $val,
					"orderby" => count($groups)
				);
				if ($val!="")$groupCount=$groupCount+1;
			}
			if (strpos($key, "group-add-") > -1) {
				$groups[] = array(
					"ID" => "",
					"group" => $val,
					"orderby" => count($groups)
				);
				if ($val!="")$groupCount=$groupCount+1;
			}
			if (strpos($key, "category-add-") > -1) {
				$categories[] = array(
					"ID" => "",
					"category" => $val,
					"orderby" => count($categories)
				);
				if ($val!="")$catCount=$catCount+1;
			}
			if (strpos($key, "category-edit-") > -1) {
				$itemID = str_replace("category-edit-", '', $key);
				$categories_id[] = $itemID;
				$categories[] = array(
					"ID" => str_replace("category-edit-", '', $key),
					"category" => $val,
					"orderby" => count($categories)
				);
				if ($val!="")$catCount=$catCount+1;
			}
		}

		if ($groupCount<=0) {
			$errors['company-groups'] = "No Groups Added, Please add at least 1 group to the company";
		}
		if ($catCount <=0) {
			$errors['company-categories'] = "No Categories Added, Please add at least 1 category to the company";
		}

	

		
		//test_array($categories);



		
		
		if (count($errors)==0){
			$ID = models\company::save($ID,$values);
			
			
			models\company::saveGroups($ID,$groups);
			models\company::saveCategories($ID,$categories);
			
			
			
			
			
		//	->saveGroups($groups)->removeGroups($group_remove_list)->saveCategories($categories)->removeCategories($category_remove_list)->show();
			
			if ($ID_orig!=$ID){
				models\company::addUser($this->user["ID"],$ID,true);
				
			}
			
			
		} 
		
		
		
		
		$return = array(
			"ID" => $ID,
			"errors" => $errors
		);
		if ($ID_orig!=$ID){
			$return['new'] = toAscii($values['company']);;
		}
		return $GLOBALS["output"]['data'] = $return;
	}

	


}
