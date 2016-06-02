<?php
namespace controllers\admin;
use \timer as timer;
use \models as models;
class pages extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		$pageID = $this->f3->get("PARAMS['pageID']");
		
		$data = models\pages::getInstance()->get($pageID);
		//test_array($pageID); 
		
		
		
		
		
		$tmpl = new \template("template.twig","app/admin/");
		$tmpl->page = array(
			"section"    => "pages",
			"sub_section"=> "page{$pageID}",
			"template"   => "pages",
			"meta"       => array(
				"title"=> "Directory | Admin | Pages",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->data=$data;
		$tmpl->pageID=$pageID;
		$tmpl->output();
	}
	function save(){
		$ID = isset($_GET['ID'])?$_GET['ID']:"";
		
		
		$values = array(
			"ID"=>$ID,
			"datein"=>date("Y-m-d H:i:s"),
			"content"=>isset($_POST['page-text'])?$_POST['page-text']:""	
		);
		
		models\pages::save($ID,$values);
		
		//test_array($ID); 
		
		$this->f3->reroute("/admin/pages/{$ID}");
		
		
	}
}
