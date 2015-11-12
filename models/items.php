<?php
namespace models;
use \timer as timer;

class items extends _ {
	private static $instance;
	//private $method;
	function __construct() {
		parent::__construct();
	}
	public static function getInstance(){
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	function get($ID){
		$timer = new timer();
		$where = "ID = '$ID'";
		
		$sql = "
			SELECT * FROM dir_items WHERE {$where}
		";
		
		$result = $this->f3->get("DB")->exec($sql);
		if (count($result)) {
			$return = $result[0];
		} else {
			$return = parent::dbStructure("dir_items");
		}
		//test_array($return);
		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return self::format($return);
	}
	function getAll($where = "", $orderby = "", $limit = "", $options = array()) {
		$timer = new timer();
		$options = array(
				"ttl" => isset($options['ttl']) ? $options['ttl'] : "",
				"args" => isset($options['args']) ? $options['args'] : array(),
		);
		$return = array();
		//test_array($options);
		
		if ($where) {
			$where = " " . $where . "";
		} else {
			$where = " 1 ";
		}
		
		if ($orderby) {
			$orderby = " ORDER BY " . $orderby;
		}
		if ($limit) {
			$limit = " LIMIT " . $limit;
		}
		
		$sql = "
			SELECT DISTINCT dir_items.*
			 FROM dir_items INNER JOIN dir_item_category ON dir_item_category.itemID = dir_items.ID
			 
			WHERE $where
			$orderby
			$limit
		";
		
		
		//test_string($sql); 
		
		$result = $this->f3->get("DB")->exec($sql, $options['args'], $options['ttl']);
		
		
		//test_array($result); 
		$return = $result;
		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return ($return);
		
	}
	
	
	
	
	static function save($ID,$values){
		$timer = new timer();
		$f3 = \base::instance();
		$user = $f3->get("user");
		//	test_array($values); 
		$IDorig = $ID;$changes = array();
		$art = new \DB\SQL\Mapper($f3->get("DB"), "dir_items");
		$art->load("ID='$ID'");
		//	test_array(array($art->ID,$ID));
		
		
		//test_array($this->get("14")); 
		foreach ($values as $key => $value) {
			$value = $f3->scrub($value,$f3->get("TAGS"));
					
			if (isset($art->$key)) {
				$art->$key =  $value;
			}
			
		}
				
		$art->save();
		$ID = ($art->ID) ? $art->ID : $art->_id;
					
		
		
		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return $ID;
	}
	static function remove($ID){
		$timer = new timer();
		$f3 = \base::instance();
		//	test_array($values); 
		$art = new \DB\SQL\Mapper($f3->get("DB"), "dir_items");
		$art->load("ID='$ID'");
		$art->erase();
		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return "done";
	}
	
	
	
	static function format($data) {
		$timer = new timer();
		$single = false;
		//	test_array($items); 
		if (isset($data['ID'])) {
			$single = true;
			$data = array($data);
		}
		//test_array($items);
		
		$i = 1;
		$n = array();
		//test_array($items);
		
		
		foreach ($data as $item) {
			$item['url'] = toAscii($item['name']);
			$n[] = $item;
		}
		
		if ($single) $n = $n[0];
		
		
		
		
		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return $n;
	}
	
	
	
}
