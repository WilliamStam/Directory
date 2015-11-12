<?php
namespace models;
use \timer as timer;

class categories extends _ {
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
			SELECT * FROM dir_categories WHERE {$where}
		";
		
		$result = $this->f3->get("DB")->exec($sql);
		if (count($result)) {
			$return = $result[0];
			$return['children'] = self::format($this->getAll("parentID='{$return['ID']}'","category ASC"));;
			
			
		} else {
			$return = parent::dbStructure("dir_categories");
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
			SELECT *, (SELECT count(ID) FROM dir_items INNER JOIN dir_item_category ON dir_item_category.itemID = dir_items.ID WHERE catID = dir_categories.ID) AS itemCount
			 FROM dir_categories
			 
			WHERE $where
			$orderby
			$limit
		";
		$sql = "
			SELECT  dir_categories.*, COUNT(dir_items.ID) as itemCount
			FROM (dir_categories LEFT JOIN dir_item_category ON dir_categories.ID = dir_item_category.catID) INNER JOIN dir_items ON dir_item_category.itemID = dir_items.ID
			WHERE $where
			GROUP BY dir_categories.ID
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
		$art = new \DB\SQL\Mapper($f3->get("DB"), "dir_categories");
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
		$user = $f3->get("user");
		//	test_array($values); 
		$IDorig = $ID;$changes = array();
		$art = new \DB\SQL\Mapper($f3->get("DB"), "dir_categories");
		$art->load("ID='$ID'");
		$art->erase();
		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return "done";
	}
	
	
	
	
	static function format($data,$childrenGrouping=false) {
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
			$item['url'] = toAscii($item['category']);
			$n[] = $item;
		}
		
		if ($single) $n = $n[0];
		
		
		$records = $n;
		if (count($records)&&!isset($n['ID']) && $childrenGrouping){
			$rows = array();
			
			foreach ($records as $row) {
				$row['children'] = array();
				$rows[$row['ID']] = $row;
			}
			
			
			
			foreach ($rows as $k => &$v) {
				if ($v['parentID'] == $v['ID']) continue;
				if (isset($rows[$v['parentID']]))	{
					$rows[$v['parentID']]['children'][] = & $v;
				}
			}
			
			foreach ($rows as $item){
				if ($item['parentID'])unset($rows[$item['ID']]);
			}
			
			//	array_splice($rows, 2);
			//test_array($rows);
			
			$n = $rows;
			$nn = array();
			foreach ($n as $key=>$item){
				$nn[] = $item;
			}
			$n = $nn;
		}
		
		
		
		
		//test_array($n); 
		
		
		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return $n;
	}
	
	
	
}
