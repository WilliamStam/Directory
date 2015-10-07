<?php
date_default_timezone_set('Africa/Johannesburg');
setlocale(LC_ALL, 'en_ZA.UTF8');
$errorPath = dirname(ini_get('error_log'));
$errorFile = $errorPath . DIRECTORY_SEPARATOR . basename(__DIR__) . "-errors.log";
ini_set("error_log", $errorFile);

if (session_id() == "") {
	$SID = @session_start();
} else $SID = session_id();
if (!$SID) {
	session_start();
	$SID = session_id();
}
$GLOBALS["output"] = array();
$GLOBALS["models"] = array();
require_once('vendor/autoload.php');

$f3 = \base::instance();
require('inc/timer.php');
require('inc/template.php');
require('inc/functions.php');
require('inc/pagination.php');
$GLOBALS['page_execute_timer'] = new timer(true);
$cfg = array();
require_once('config.default.inc.php');
if (file_exists("config.inc.php")) {
	require_once('config.inc.php');
}

$f3->set('AUTOLOAD', './|lib/|controllers/|inc/|/modules/');
$f3->set('PLUGINS', 'vendor/bcosca/fatfree/lib/');
$f3->set('CACHE', true);

$f3->set('DB', new DB\SQL('mysql:host=' . $cfg['DB']['host'] . ';dbname=' . $cfg['DB']['database'] . '', $cfg['DB']['username'], $cfg['DB']['password']));
$f3->set('cfg', $cfg);
$f3->set('DEBUG',3);


//$f3->set('QUIET', TRUE);

$f3->set('UI', 'app/|media/');
$f3->set('MEDIA', './media/|'.$cfg['media']);
$f3->set('TZ', 'Africa/Johannesburg');

$f3->set('TAGS', 'p,br,b,strong,i,italics,em,h1,h2,h3,h4,h5,h6,div,span,blockquote,pre,cite,ol,li,ul');

$f3->set("menu", array());
$f3->set("company", array());

//$f3->set('ERRORFILE', $errorFile);
//$f3->set('ONERROR', 'Error::handler');
$f3->set('ONERRORd',
	function($f3) {
		// recursively clear existing output buffers:
		while (ob_get_level())
			ob_end_clean();
		// your fresh page here:
		echo $f3->get('ERROR.text');
		print_r($f3->get('ERROR.stack'));
	}
);

$version = date("YmdH");
if (file_exists("./.git/refs/heads/" . $cfg['git']['branch'])) {
	$version = file_get_contents("./.git/refs/heads/" . $cfg['git']['branch']);
	$version = substr(base_convert(md5($version), 16, 10), -10);
}

$minVersion = preg_replace("/[^0-9]/", "", $version);
$f3->set('_version', $version);
$f3->set('_v', $minVersion);



$uID = isset($_SESSION['uID']) ? $_SESSION['uID'] : "";
$username = isset($_REQUEST['login_email']) ? $_REQUEST['login_email'] : "";
$password = isset($_REQUEST['login_password']) ? $_REQUEST['login_password'] : "";

$userO = new \models\user();
//$uID = "2";





if ($username && $password) {
	$uID = $userO->login($username, $password);
		
	$uri = $_SERVER['REQUEST_URI'];
	$uri = str_replace("login_email=","",$uri);
	$uri = str_replace("login_password=","",$uri);
	if (isset($_GET['login_email'])) $uri = str_replace($_GET['login_email'],"",$uri);
	if (isset($_GET['login_password'])) $uri = str_replace($_GET['login_password'],"",$uri);

	$uri = str_replace("&&","&",$uri);
	$uri = str_replace("&&","&",$uri);
	$uri = str_replace("&&","&",$uri);
	$uri = str_replace("&&","&",$uri);
	$uri = str_replace("&&","&",$uri);
	$uri = str_replace("?&","?",$uri);
	if ($uri=="?")$uri = "";
	
	$url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $uri;
	$f3->reroute($url);
}
$user = $userO->get($uID);
if (isset($_GET['auID']) && $user['su']=='1'){
	$_SESSION['uID'] = $_GET['auID'];
	$user = $userO->get($_GET['auID']);
}


$f3->set('user', $user);
$f3->set('session', $SID);




$f3->route('GET /txt', function ($f3) {
	echo "Hallo World";

}
);

$f3->route('GET|POST /login', 'controllers\login->page');
$f3->route('GET|POST /', 'controllers\home->page');





$f3->route('GET|POST /logout', function ($f3, $params) use ($user) {
	session_start();
	session_unset();
	session_destroy();
	session_write_close();
	setcookie(session_name(),'',0,'/');
	session_regenerate_id(true);
	
	//session_destroy();
	$f3->reroute("/login");
});







$f3->route("GET|POST /save/@function", function ($app, $params) {
	$app->call("controllers\\admin\\save\\save->" . $params['function']);
}
);
$f3->route("GET|POST /save/@class/@function", function ($app, $params) {
	$app->call("controllers\\save\\" . $params['class'] . "->" . $params['function']);
}
);
$f3->route("GET|POST /save/@folder/@class/@function", function ($app, $params) {
	$app->call("controllers\\save\\" . $params['folder'] . "\\" . $params['class'] . "->" . $params['function']);
}
);
$f3->route("GET|POST /data/@function", function ($app, $params) {
	$app->call("controllers\\data\\data->" . $params['function']);
}
);
$f3->route("GET|POST /data/@class/@function", function ($app, $params) {
	//test_array($params); 
	$app->call("controllers\\data\\" . $params['class'] . "->" . $params['function']);
}
);
$f3->route("GET|POST /data/@folder/@class/@function", function ($app, $params) {
	$app->call("controllers\\data\\" . $params['folder'] . "\\" . $params['class'] . "->" . $params['function']);
}
);

$f3->route("GET|POST /internal/emails/@class/@function", function ($app, $params) {
	$app->call("controllers\\emails\\" . $params['class'] . "->" . $params['function']);
}
);






$f3->route("GET|POST /keepalive", function ($app, $params) {
	$user = $app->get("user");
	unset($user["password"]);
	unset($user["global_admin"]);
	test_array($user);	
});








$f3->route("GET|POST /send", function ($app, $params) {
	$path = './media/1/';
	
	$files = array(
		//"test.jpg",
			//"2019592250.jpg",
			"AdbookerV3.pdf",
			//"119356313115.jpg"
	);

// Helper function courtesy of https://github.com/guzzle/guzzle/blob/3a0787217e6c0246b457e637ddd33332efea1d2a/src/Guzzle/Http/Message/PostFile.php#L90
	function getCurlValue($filename, $contentType, $postname)
	{
		// PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
		// See: https://wiki.php.net/rfc/curl-file-upload
		if (function_exists('curl_file_create')) {
			return curl_file_create($filename, $contentType, $postname);
		}
		
		// Use the old style if using an older version of PHP
		$value = "@{$this->filename};filename=" . $postname;
		if ($contentType) {
			$value .= ';type=' . $contentType;
		}
		
		return $value;
	}
	$data = array(
		"ID"=>"123",
			"name"=>"william"
	);
	$web = new \Web();
	$i = 0;
	foreach ($files as $item){
		$i = $i + 1;
		$mime = $web->mime($item);
		$cfile = getCurlValue($path.$item,$mime,$item);
		$data["file$i"] = $cfile;
	}
	//test_array($data); 
	//$filename = './media/1/test.jpg';
	

//NOTE: The top level key in the array is important, as some apis will insist that it is 'file'.
	//$data = array('file' => $cfile);
	
	$ch = curl_init();
	$options = array(CURLOPT_URL => 'http://directory.local/receive',
			CURLOPT_RETURNTRANSFER => true,
			CURLINFO_HEADER_OUT => true, //Request header
			CURLOPT_HEADER => true, //Return header
			CURLOPT_SSL_VERIFYPEER => false, //Don't veryify server certificate
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $data
	);
	
	curl_setopt_array($ch, $options);
	$result = curl_exec($ch);
	$header_info = curl_getinfo($ch,CURLINFO_HEADER_OUT);
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($result, 0, $header_size);
	$body = substr($result, $header_size);
	curl_close($ch);
	//exit();
	
	test_array(array(
		'header'=>array(
			"sent"=>$header_info,
			"recieved"=>$header
		),
		"body"=>$body
	));
	/*
	echo " <p>Raw Result: {$result}
    <p>Header Sent: {$header_info}</p>
    <p>Header Received: {$header}</p>
    <p>Body: {$body}</p>";
	*/
});


$f3->route("GET|POST /receive", function ($app, $params) {
	
	//$folder = $app->get("MEDIA");
	//$folder = $folder . "files/temp/";
	$folder = './media/2/';
	if (!file_exists($folder)) {
		mkdir($folder, 0755, true);
	}
	
	//test_array($_POST); 
	
	//test_array($_FILES); 
	$return = array();
	foreach ($_FILES as $key => $item){
		$fileName = isset($item["name"]) ? $item["name"] : '';
		//print_r($key);
		//$_FILES['file'] = $item;
		echo json_encode(upload($_POST['ID']."-".$fileName, $folder, $item));
	}
//	test_array($_POST); 
	
	//$result['return'] = $return;
	//header("Content-Type: application/json");
	//test_array($result); 
	
	
	
});










$f3->route('GET /php', function () {
	phpinfo();
	exit();
});

$f3->run();



	

$models = $GLOBALS['models'];
$t = array();
foreach ($models as $model) {
	$c = array();
	foreach ($model['m'] as $method) {
		$c[] = $method;
	}
	$model['m'] = $c;
	$t[] = $model;
}



$models = $t;
$pageTime = $GLOBALS['page_execute_timer']->stop("Page Execute");

$GLOBALS["output"]['timer'] = $GLOBALS['timer'];

if ($user['global_admin']=='1'){
	$GLOBALS["output"]['models'] = $models;
}



$GLOBALS["output"]['page'] = array(
	"page" => $_SERVER['REQUEST_URI'],
	"time" => $pageTime
);


if ($f3->get("ERROR")){
	exit();
}

if (($f3->get("AJAX") && ($f3->get("__runTemplate")==false) || $f3->get("__runJSON"))) {
	header("Content-Type: application/json");
	echo json_encode($GLOBALS["output"]);
} else {

	//if (strpos())
	if ($f3->get("NOTIMERS")){
		exit();
	}
	

	echo '
					<script type="text/javascript">
				    //  updatetimerlist(' . json_encode($GLOBALS["output"]) . ');
					</script>
					</body>
</html>';

}



?>
