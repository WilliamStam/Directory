<?php
namespace controllers;
use \timer as timer;
use \models as models;
class list_alphabet extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$user = $this->f3->get("user");
		
		$letter = $this->f3->get("PARAMS['letter']");
		//$category = models\categories::getInstance()->get($catID);
		
		
		
		$tmpl = new \template("template.twig");
		$tmpl->page = array(
			"section"    => "alphabet",
			"sub_section"=> "$letter",
			"template"   => "list_alphabet",
			"meta"       => array(
				"title"=> "Directory | starts with '$letter'",
			),
			"css"=>"",
			"js"=>"",
		);
		$tmpl->letter = $letter;
		$tmpl->output();
	}
}
