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

$userO = new \models\users();
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



$categories = models\categories::getInstance()->getAll("","category ASC");
$f3->set('user', $user);
$f3->set('session', $SID);
$f3->set('categories', $categories);
$f3->set('itemCount', count(models\items::getInstance()->getAll("")));









$f3->route('GET /txt', function ($f3) {
	echo "Hallo World";

}
);

$f3->route('GET|POST /login', 'controllers\login->page');
$f3->route('GET|POST /', 'controllers\home->page');

$f3->route('GET|POST /map', 'controllers\map->page');
$f3->route('GET|POST /list/@letter', 'controllers\list_alphabet->page');
$f3->route('GET|POST /list/@catID/@url', 'controllers\list_category->page');
$f3->route('GET|POST /item/@ID/@url', 'controllers\item_details->page');


$f3->route('GET|POST /search', 'controllers\search->page');


$f3->route('GET|POST /about', 'controllers\about->page');
$f3->route('GET|POST /contact', 'controllers\contact->page');




$f3->route('GET|POST /admin', 'controllers\admin\items->page');
$f3->route('GET|POST /admin/items', 'controllers\admin\items->page');
$f3->route('GET|POST /admin/categories', 'controllers\admin\categories->page');
$f3->route('GET|POST /admin/users', 'controllers\admin\users->page');
$f3->route('GET|POST /admin/pages/@pageID', 'controllers\admin\pages->page');
$f3->route('GET|POST /admin/pages/save', 'controllers\admin\pages->save');



$f3->route('GET|POST /list', 'controllers\_categories->page');
$f3->route('GET|POST /items', 'controllers\_items->page');


$f3->route("GET|POST /admin/save/@function", function ($app, $params) {
	$app->call("controllers\\admin\\save\\save->" . $params['function']);
}
);
$f3->route("GET|POST /admin/save/@class/@function", function ($app, $params) {
	$app->call("controllers\\admin\\save\\" . $params['class'] . "->" . $params['function']);
}
);
$f3->route("GET|POST /admin/save/@folder/@class/@function", function ($app, $params) {
	$app->call("controllers\\admin\\save\\" . $params['folder'] . "\\" . $params['class'] . "->" . $params['function']);
}
);
$f3->route("GET|POST /admin/data/@function", function ($app, $params) {
	$app->call("controllers\\admin\\data\\data->" . $params['function']);
}
);
$f3->route("GET|POST /admin/data/@class/@function", function ($app, $params) {
	$app->call("controllers\\admin\\data\\" . $params['class'] . "->" . $params['function']);
}
);
$f3->route("GET|POST /admin/data/@folder/@class/@function", function ($app, $params) {
	$app->call("controllers\\admin\\data\\" . $params['folder'] . "\\" . $params['class'] . "->" . $params['function']);
}
);

$f3->route('GET /thumbnail/@width/@height', function ($f3, $params) {
	$file = (isset($_GET['file'])) ? $_GET['file'] : "";
	$crop = (isset($_GET['crop'])) ? $_GET['crop'] : "";
	$enlarge = (isset($_GET['enlarge'])) ? $_GET['enlarge'] : "";
	
	if ($crop=="true"){
		$crop = true;
	} else {
		$crop=false;
	}
	if ($enlarge=="true"){
		$enlarge = true;
	} else {
		$enlarge = false;
	}
	
	$width = $params['width'];
	$height = $params['height'];
	
	
	
	$cfg = $f3->get("cfg");
	$folder = $cfg['media'];
	
	$path = $folder . $file;
	$path = $f3->fixslashes($path);
	$path = str_replace("//","/",$path);
	
	//test_string($path);
	
	
	$mime = mime_content_type($path);
	
	

	
	if ($mime=="application/pdf"){
		$thumb = DIRECTORY_SEPARATOR . str_replace(".pdf", "_thumb.png", $file);
		
		//test_array($thumb); 
		
		
		if (!file_exists($folder . $thumb) && file_exists($folder . $file)) {
			$exportPath = $folder . $thumb;
			$res = "96";
			$pdf = $folder . $file;
			
			$str = "gs -dCOLORSCREEN -dNOPAUSE -box -sDEVICE=png16m -dUseCIEColor -dTextAlphaBits=4 -dFirstPage=1 -dLastPage=1 -dGraphicsAlphaBits=4 -o$exportPath -r$res  $pdf";
			
			exec($str);
			
			\general::remove_white($folder . $thumb);
		}
		
		$file = DIRECTORY_SEPARATOR . str_replace(".pdf", ".png", $file);
	}

//test_array($file); 
	
	if ($file) {
		$gen = new \general();
		$gen->thumbnail($file, $width, $height, $crop, $enlarge);
	}
	
	
});





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



function get_websafe() {
	$vals = array('00', '33', '66', '99', 'cc', 'ff');
	$colors = array();
	foreach ($vals as $r) {
		foreach ($vals as $g) {
			foreach ($vals as $b) {
				$colors[] = $r.$g.$b;
			}
		}
	}
	return array_reverse($colors);
}





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

$GLOBALS["output"]['models'] = $models;



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
				      updatetimerlist(' . json_encode($GLOBALS["output"]) . ');
					</script>
					</body>
</html>';

}



?>
