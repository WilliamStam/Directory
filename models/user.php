<?php
namespace models;
use \timer as timer;

class user extends _ {
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
		if (!is_numeric($ID)) {
			$where = "email = '$ID'";
		}


		$result = $this->f3->get("DB")->exec("
			SELECT *
			FROM tb_users
			WHERE $where;
		"
		);


		if (count($result)) {
			$return = $result[0];
		} else {
			$return = parent::dbStructure("tb_users");
			
		}
		
		//test_array($return);
		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		
		return self::format($return);
	}
	function login($username, $password) {
		$timer = new timer();

		$ID = "";


		setcookie("username", $username, time() + 31536000, "/");


		$password_hash = $password;

		$password_hash = md5(md5("dire-").$password.md5("-radiant"));

		$result = $this->f3->get("DB")->exec("
			SELECT ID, email FROM tb_users WHERE email ='$username' AND password = '$password_hash'
		");


		if (count($result)) {
			$result = $result[0];
			$ID = $result['ID'];

			if ($ID!=""){
				$art = new \DB\SQL\Mapper($this->f3->get("DB"), "tb_users");
				$art->load("ID = '{$ID}'");
				$art->lastLoggedin = date("Y-m-d H:i:s");
				$art->save();
			}
			
			
			$_SESSION['uID'] = $ID;
			if (isset($_COOKIE['username'])) {
				$_COOKIE['username'] = $result['email'];
			} else {
				setcookie("username", $result['email'], time() + 31536000, "/");
			}
			
		} else {
		}

		$return = $ID;
		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return $return;
	}
	
	
	static function format($data){
		$timer = new timer();
		$f3 = \base::instance();

 

		$single = false;
		if (isset($data['ID'])) {
			$single = true;
			$data = array($data);
		}

		$i = 1;
		$n = array();
		//test_array($items); 

		foreach ($data as $item){
			$n[] = $item;
		}

		if ($single) $n = $n[0];



		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());

		return $n;
	}
	
	

}
