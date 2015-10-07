<?php


function toAscii($str, $replace=array(), $delimiter='-') {
	if( !empty($replace) ) {
		$str = str_replace((array)$replace, ' ', $str);
	}

	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	return $clean;
}


function isLocal() {
	if (file_exists("D:/web/local.txt") || file_exists("C:/web/local.txt")) {
		return true;
	} else return false;
}

function clean_url($url){

	if (strpos($url, "?")) {
		$url = substr($url, 0, strpos($url, "?"));
	}
	return $url;
}
function return_url($url){

	$return = base64_encode($url);
	$return = urlencode($return);
	return $return;
}



function highlight($needle, $haystack) {
	$ind = stripos($haystack, $needle);
	$len = strlen($needle);
	if ($ind !== false) {
		return substr($haystack, 0, $ind) . "<span class='highlight'>" . substr($haystack, $ind, $len) . "</span>" . highlight($needle, substr($haystack, $ind + $len));
	} else return $haystack;
}
function is_bot() {

	$str = preg_match('/robot|spider|crawler|curl|bot|Slurp|^$/i', $_SERVER['HTTP_USER_AGENT']);
	
	if ($str=='1'){
		return true; // Is a bot
	} else {
		return false; // Not a bot
	}
	
	$botlist = array(
		"Teoma",
		"bingbot",
		"alexa",
		"froogle",
		"Gigabot",
		"inktomi",
		"looksmart",
		"URL_Spider_SQL",
		"Firefly",
		"NationalDirectory",
		"Ask Jeeves",
		"TECNOSEEK",
		"InfoSeek",
		"WebFindBot",
		"girafabot",
		"crawler",
		"www.galaxy.com",
		"Googlebot",
		"Googlebot",
		"Scooter",
		"Slurp",
		"msnbot",
		"appie",
		"FAST",
		"WebBug",
		"Spade",
		"ZyBorg",
		"rabaz",
		"Baiduspider",
		"Feedfetcher-Google",
		"TechnoratiSnoop",
		"Rankivabot",
		"Mediapartners-Google",
		"Sogou web spider",
		"WebAlta Crawler",
		"TweetmemeBot",
		"Butterfly",
		"Twitturls",
		"Me.dium",
		"Twiceler"
	);

	foreach ($botlist as $bot) {
		if (strpos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) return true; // Is a bot
	}

	return false; // Not a bot
}

function sanitize_output($buffer) {
	$search = array(
		'/\>[^\S ]+/s',
		//strip whitespaces after tags, except space
		'/[^\S ]+\</s',
		//strip whitespaces before tags, except space
		'/(\s)+/s'
		// shorten multiple whitespace sequences
	);
	$replace = array(
		'>',
		'<',
		'\\1'
	);
	//$buffer = preg_replace($search, $replace, $buffer);
	return $buffer;
}

function rev_nl2br($string, $line_break = PHP_EOL) {
	/*	$string = preg_replace('#<\/p>#i', "", $string);
		$string = preg_replace('#<p>#i', "", $string);

	*/
	return $string;


}

function is_ajax() {
	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}


function siteURL() {
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$domainName = $_SERVER['HTTP_HOST'];
	return $protocol . $domainName;
}

function file_size($size) {
	$unit = array(
		'b',
		'kb',
		'mb',
		'gb',
		'tb',
		'pb'
	);
	return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

function timesince($tsmp) {
	if (!$tsmp) return "";
	$diffu = array(
		'seconds' => 2,
		'minutes' => 120,
		'hours'   => 7200,
		'days'    => 172800,
		'months'  => 5259487,
		'years'   => 63113851
	);
	$diff = time() - strtotime($tsmp);
	$dt = '0 seconds ago';
	foreach ($diffu as $u => $n) {
		if ($diff > $n) {
			$dt = floor($diff / (.5 * $n)) . ' ' . $u . ' ago';
		}
	}
	return $dt;
}

function curl_get_contents($url) {
	$ch = curl_init();
	$timeout = 5; // set to zero for no timeout
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	curl_close($ch);

	return $file_contents;
}

function currency($number) {

	$number = $GLOBALS['cfg']['currency']['sign'] . number_format($number, 2, '.', $GLOBALS['cfg']['currency']['separator']);
	return str_replace(" ", "&nbsp;", $number);

}

function test_array($array) {
	header("Content-Type: application/json");
	$f3 = \Base::instance();
	$f3->set("__testJson",true);
	echo json_encode($array);
	exit();
}

function test_string($array) {
	header("Content-Type: text/html");
	$f3 = \Base::instance();
	$f3->set("__testString",true);
	echo $array;
	exit();
}

function bt_loop($trace) {
	if (isset($trace['object'])) unset($trace['object']);
	if (isset($trace['type'])) unset($trace['type']);


	$args = array();
	foreach ($trace['args'] as $arg) {
		if (is_array($arg)) {
			if (count($arg) < 5) {
				$args[] = $arg;
			} else {
				$args[] = "Array " . count($arg);
			}

		} else {
			$args[] = $arg;
		}

	}
	$trace['args'] = $args;

	return $trace;
}


function sortBy(&$arr, $col, $dir = SORT_ASC) {
	$sort_col = array();
	foreach ($arr as $key=> $row) {
		$sort_col[$key] = $row[$col];
	}

	array_multisort($sort_col, $dir, $arr);
}



function filter(&$value) {
	$value = (is_string($value)) ?htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : $value;
}
function form_display(&$value) {
	if ($value){
		//$value = utf8_encode($value);
		$value = str_replace('"',"&quot;",$value);
		//$value = str_replace('Ã«',"ë",$value);
		//$value = htmlentities($value,'','UTF-8');
	}

}

if (!function_exists('http_response_code')) {
	function http_response_code($code = null)
	{
		static $defaultCode = 200;

		if (null != $code) {
			switch ($code) {
				case 100: $text = 'Continue'; break;                        // RFC2616
				case 101: $text = 'Switching Protocols'; break;             // RFC2616
				case 102: $text = 'Processing'; break;                      // RFC2518

				case 200: $text = 'OK'; break;                              // RFC2616
				case 201: $text = 'Created'; break;                         // RFC2616
				case 202: $text = 'Accepted'; break;                        // RFC2616
				case 203: $text = 'Non-Authoritative Information'; break;   // RFC2616
				case 204: $text = 'No Content'; break;                      // RFC2616
				case 205: $text = 'Reset Content'; break;                   // RFC2616
				case 206: $text = 'Partial Content'; break;                 // RFC2616
				case 207: $text = 'Multi-Status'; break;                    // RFC4918
				case 208: $text = 'Already Reported'; break;                // RFC5842
				case 226: $text = 'IM Used'; break;                         // RFC3229

				case 300: $text = 'Multiple Choices'; break;                // RFC2616
				case 301: $text = 'Moved Permanently'; break;               // RFC2616
				case 302: $text = 'Found'; break;                           // RFC2616
				case 303: $text = 'See Other'; break;                       // RFC2616
				case 304: $text = 'Not Modified'; break;                    // RFC2616
				case 305: $text = 'Use Proxy'; break;                       // RFC2616
				case 306: $text = 'Reserved'; break;                        // RFC2616
				case 307: $text = 'Temporary Redirect'; break;              // RFC2616
				case 308: $text = 'Permanent Redirect'; break;              // RFC-reschke-http-status-308-07

				case 400: $text = 'Bad Request'; break;                     // RFC2616
				case 401: $text = 'Unauthorized'; break;                    // RFC2616
				case 402: $text = 'Payment Required'; break;                // RFC2616
				case 403: $text = 'Forbidden'; break;                       // RFC2616
				case 404: $text = 'Not Found'; break;                       // RFC2616
				case 405: $text = 'Method Not Allowed'; break;              // RFC2616
				case 406: $text = 'Not Acceptable'; break;                  // RFC2616
				case 407: $text = 'Proxy Authentication Required'; break;   // RFC2616
				case 408: $text = 'Request Timeout'; break;                 // RFC2616
				case 409: $text = 'Conflict'; break;                        // RFC2616
				case 410: $text = 'Gone'; break;                            // RFC2616
				case 411: $text = 'Length Required'; break;                 // RFC2616
				case 412: $text = 'Precondition Failed'; break;             // RFC2616
				case 413: $text = 'Request Entity Too Large'; break;        // RFC2616
				case 414: $text = 'Request-URI Too Long'; break;            // RFC2616
				case 415: $text = 'Unsupported Media Type'; break;          // RFC2616
				case 416: $text = 'Requested Range Not Satisfiable'; break; // RFC2616
				case 417: $text = 'Expectation Failed'; break;              // RFC2616
				case 422: $text = 'Unprocessable Entity'; break;            // RFC4918
				case 423: $text = 'Locked'; break;                          // RFC4918
				case 424: $text = 'Failed Dependency'; break;               // RFC4918
				case 426: $text = 'Upgrade Required'; break;                // RFC2817
				case 428: $text = 'Precondition Required'; break;           // RFC6585
				case 429: $text = 'Too Many Requests'; break;               // RFC6585
				case 431: $text = 'Request Header Fields Too Large'; break; // RFC6585

				case 500: $text = 'Internal Server Error'; break;           // RFC2616
				case 501: $text = 'Not Implemented'; break;                 // RFC2616
				case 502: $text = 'Bad Gateway'; break;                     // RFC2616
				case 503: $text = 'Service Unavailable'; break;             // RFC2616
				case 504: $text = 'Gateway Timeout'; break;                 // RFC2616
				case 505: $text = 'HTTP Version Not Supported'; break;      // RFC2616
				case 506: $text = 'Variant Also Negotiates'; break;         // RFC2295
				case 507: $text = 'Insufficient Storage'; break;            // RFC4918
				case 508: $text = 'Loop Detected'; break;                   // RFC5842
				case 510: $text = 'Not Extended'; break;                    // RFC2774
				case 511: $text = 'Network Authentication Required'; break; // RFC6585

				default:
					$code = 500;
					$text = 'Internal Server Error';
			}

			$defaultCode = $code;

			$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
			header($protocol . ' ' . $code . ' ' . $text);
		}

		return $defaultCode;
	}
}
function upload($filename,$path,$FILE=''){
	
	//test_array(func_get_args()); 
	// HTTP headers for no cache etc
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

// Settings
	$targetDir = realpath($path);

//$targetDir = 'uploads';
	
	//$targetDir = "D:\\Web\\LiN\\media\\files\\temp\\";
	
	if (!file_exists($targetDir)) @mkdir($targetDir);
	//test_array($targetDir); 
	$cleanupTargetDir = true; // Remove old files
	$maxFileAge = 5 * 3600; // Temp file age in seconds

// 5 minutes execution time
	@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);

// Get parameters
	$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
	$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

// Clean the fileName for security reasons
	$fileName = preg_replace('/[^\w\._]+/', '_', $filename);

// Make sure the fileName is unique but only if chunking is disabled
	if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
		$ext = strrpos($fileName, '.');
		$fileName_a = substr($fileName, 0, $ext);
		$fileName_b = substr($fileName, $ext);
		
		$count = 1;
		while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b)) $count++;
		
		if ($fileName){
			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}
		
	}
	
	$filePath = ($targetDir). DIRECTORY_SEPARATOR . $fileName;
	
	//	echo json_encode(array("path"=>$targetDir,"fileName"=>$fileName,"fullpath"=>$filePath)); 

// Create target dir
	//if (!file_exists($targetDir)) @mkdir($targetDir);

// Remove old temp files
	if ($cleanupTargetDir) {
		if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
				
				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		} else {
			return('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
		}
	}

// Look for the content type header
	if (isset($_SERVER["HTTP_CONTENT_TYPE"])) $contentType = $_SERVER["HTTP_CONTENT_TYPE"];
	
	if (isset($_SERVER["CONTENT_TYPE"])) $contentType = $_SERVER["CONTENT_TYPE"];

// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
	if (strpos($contentType, "multipart") !== false) {
		if ($FILE==''){
			$FILE = $_FILES['file'];
		}
		if (isset($FILE['tmp_name']) && is_uploaded_file($FILE['tmp_name'])) {
			// Open temp file
			$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = @fopen($FILE['tmp_name'], "rb");
				
				if ($in) {
					while ($buff = fread($in, 4096)) fwrite($out, $buff);
				} else
					return('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				@fclose($in);
				@fclose($out);
				@unlink($FILE['tmp_name']);
			} else
				return('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		} else
			return('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	} else {
		// Open temp file
		$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = @fopen("php://input", "rb");
			
			if ($in) {
				while ($buff = fread($in, 4096)) fwrite($out, $buff);
			} else
				return('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			
			@fclose($in);
			@fclose($out);
		} else
			return('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	}

// Check if file has been uploaded
	if (!$chunks || $chunk == $chunks - 1) {
		// Strip the temp .part suffix off
		rename("{$filePath}.part", $filePath);
	}
	
	return('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
	
}